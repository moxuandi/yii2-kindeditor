<?php
namespace moxuandi\kindeditor;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
use moxuandi\helpers\Uploader;

/**
 * KindEditor 接收上传图片控制器.
 *
 * @author  zhangmoxuan <1104984259@qq.com>
 * @link  http://www.zhangmoxuan.com
 * @QQ  1104984259
 * @Date  2017-7-15
 * @see http://kindeditor.net
 */
class KindEditorAction extends Action
{
    public $config = [];  // 配置接口


    public function init()
    {
        Yii::$app->request->enableCsrfValidation = false;  // 关闭csrf
        $_config = require(__DIR__ . '/config.php');  // 默认上传配置
        $this->config = array_merge($_config, $this->config);
        parent::init();
    }

    public function run()
    {
        switch(Yii::$app->request->get('action')){
            case 'fileManagerJson': self::actionList(); break;
            case 'uploadJson': self::actionUpload(); break;
            default: break;
        }
    }

    /**
     * 处理上传
     */
    private function actionUpload()
    {
        switch(Yii::$app->request->get('dir')){
            case 'image':
                $config = [
                    'maxSize' => $this->config['imageMaxSize'],
                    'allowFiles' => $this->config['imageAllowFiles'],
                    'pathFormat' => $this->config['imagePathFormat'],
                    'thumbStatus' => $this->config['thumbStatus'],
                    'thumbWidth' => $this->config['thumbWidth'],
                    'thumbHeight' => $this->config['thumbHeight'],
                    'thumbCut' => $this->config['thumbCut'],
                ];
                break;
            case 'flash':
                $config = [
                    'maxSize' => $this->config['flashMaxSize'],
                    'allowFiles' => $this->config['flashAllowFiles'],
                    'pathFormat' => $this->config['flashPathFormat'],
                ];
                break;
            case 'media':
                $config = [
                    'maxSize' => $this->config['mediaMaxSize'],
                    'allowFiles' => $this->config['mediaAllowFiles'],
                    'pathFormat' => $this->config['mediaPathFormat'],
                ];
                break;
            case 'file':
                $config = [
                    'maxSize' => $this->config['fileMaxSize'],
                    'allowFiles' => $this->config['fileAllowFiles'],
                    'pathFormat' => $this->config['filePathFormat'],
                ];
                break;
            default:
                $config = [];
                break;
        }

        // 生成上传实例对象并完成上传, 返回结果数据
        $up = new Uploader('imgFile', $config, 'upload', $this->config['saveDatabase']);
        //header('Content-type: text/html; charset=UTF-8');
        //header('Content-type: application/json; charset=UTF-8');  // IE下可能出错，怀疑火狐下批量上传失败，也是这个原因
        if($up->stateInfo == 'SUCCESS'){
            echo Json::encode(['error'=>0, 'url'=>'/' . $up->fullName]);
            exit();
        }else{
            echo $up->stateInfo;
            exit();
        }
    }

    /**
     * 列出已上传的文件列表
     */
    private function actionList()
    {
        $dir = Yii::$app->request->get('dir');
        if(!in_array($dir, ['image', 'flash', 'media', 'file'])){
            echo '无效的目录名。'; exit();
        }

        $root_path = Yii::getAlias('@webroot') . '/uploads/' . $dir . '/';
        $root_url = Yii::$app->request->hostInfo . '/uploads/' . $dir . '/';
        if(!file_exists($root_path)){
            mkdir($root_path, 0777, true);
        }

        // 根据path参数，设置各路径和URL
        $path = Yii::$app->request->get('path');
        if(empty($path)){
            $current_path = realpath($root_path) . '/';
            $current_url = $root_url;
            $current_dir_path = '';
            $moveup_dir_path = '';
        }else{
            $current_path = realpath($root_path) . '/' . $path;
            $current_url = $root_url . $path;
            $current_dir_path = $path;
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }

        // 不允许使用'..'移动到上一级目录
        if(preg_match('/\.\./', $current_path)){
            echo '访问不被允许。'; exit;
        }

        // 最后一个字符不是'/'
        if(!preg_match('/\/$/', $current_path)){
            echo '参数无效。'; exit;
        }

        // 目录不存在或不是目录
        if(!file_exists($current_path) || !is_dir($current_path)){
            echo '目录不存在。'; exit;
        }

        // 遍历目录取得文件信息
        $file_list = [];
        if($handle = opendir($current_path)){
            $i = 0;
            while(false !== ($filename = readdir($handle))){
                if($filename{0} == '.'){
                    continue;
                }
                $file = $current_path . $filename;
                if(is_dir($file)){
                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                }else{
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);   //文件大小
                    $file_list[$i]['dir_path'] = '';
                    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));    //小写的扩展名
                    $file_list[$i]['is_photo'] = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);     //是否是图片（扩展名为$ext_arr中的一个）
                    $file_list[$i]['filetype'] = $file_ext; //扩展名
                }
                $file_list[$i]['filename'] = $filename; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
                $i++;
            }
            closedir($handle);
        }

        usort($file_list, [$this, 'cmp_func']); //用cmp_func()函数对数组进行排序

        $result = array();
        $result['moveup_dir_path'] = $moveup_dir_path;      //相对于根目录的上一级目录
        $result['current_dir_path'] = $current_dir_path;    //相对于根目录的当前目录
        $result['current_url'] = $current_url;              //当前目录的URL
        $result['total_count'] = count($file_list);         //文件数
        $result['file_list'] = $file_list;                  //文件列表数组

        header('Content-type: application/json; charset=UTF-8');
        echo Json::encode($result);
    }

    /**
     * 排序
     * @param array $a
     * @param array $b
     * @return int
     */
    public function cmp_func($a, $b)
    {
        // 排序形式，name or size or type
        $order = strtolower(Yii::$app->request->get('order', 'name'));

        if($a['is_dir'] && !$b['is_dir']){
            return -1;
        }elseif(!$a['is_dir'] && $b['is_dir']){
            return 1;
        }else{
            if($order == 'size'){
                if($a['filesize'] > $b['filesize']){
                    return 1;
                }elseif($a['filesize'] < $b['filesize']){
                    return -1;
                }else{
                    return 0;
                }
            }elseif($order == 'type'){
                return strcmp($a['filetype'], $b['filetype']);
            }else{
                return strcmp($a['filename'], $b['filename']);
            }
        }
    }
}
