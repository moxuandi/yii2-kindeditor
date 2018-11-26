<?php
namespace moxuandi\kindeditor;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * KindEditor renders a editor js plugin for classic editing.
 *
 * @author  zhangmoxuan <1104984259@qq.com>
 * @link  http://www.zhangmoxuan.com
 * @QQ  1104984259
 * @Date  2017-12-23
 * @see http://kindeditor.net
 */
class KindEditor extends InputWidget
{
    /**
     * @var array 配置接口, 参阅 KindEditor 官方文档(http://kindeditor.net/docs/option.html)
     */
    public $editorOptions = [];
    /**
     * @var string 编辑器的类型, 可用值有:
     * - `textEditor`: HTML 编辑器(默认值)
     * - `colorPicker`: 取色器, 无法自定义颜色(编辑器调用时正常)
     * - `fileDialog`: 上传文件
     * - `imageDialog`: 上传图片(网络图片 + 本地上传)
     * - `remoteImageDialog`: 上传图片(网络图片)
     * - `localImageDialog`: 上传图片(本地上传)
     * - `imageManager`: 浏览服务器(图片)
     * - `flashManager`: 浏览服务器(Flash)
     * - `mediaManager`: 浏览服务器(视音频)
     * - `fileManager`: 浏览服务器(文件)
     * - `uploadButton`: 自定义上传按钮(不建议)
     * - `multiImageDialog`: 批量上传图片(未实现)
     */
    public $editorType;
    /**
     * @var string 单独调用插件时, 渲染 HTML 的模板
     */
    public $editorTemplate = "<div class='input-group'>{input}<span class='input-group-btn'>{button}</span></div>";
    /**
     * @var array input 输入域的 HTML 属性
     */
    public $inputOptions = ['class' => 'form-control'];
    /**
     * @var array button 按钮的 HTML 属性. 特殊属性:
     * - `label`: button 按钮的文本内容(不是 label 属性)
     */
    public $buttonOptions = ['class' => 'btn btn-default'];
    /**
     * @var bool 为`true`时, 将会添加`extraFileUploadParams`参数, 以使单独调用图片上传时能自定义缩略图大小等信息.
     */
    public $dialogImage = false;


    public function init()
    {
        if($this->hasModel()){
            parent::init();
            $this->id = Html::getInputId($this->model, $this->attribute);
        }elseif($this->attribute){
            $this->id .= '_' . $this->attribute;
        }
        if(isset($this->inputOptions['id'])){
            $this->id = $this->inputOptions['id'];
        }else{
            $this->inputOptions['id'] = $this->id;
        }

        $_options = [
            'allowFileManager' => true,  // 显示浏览远程服务器按钮
            'uploadJson' => Url::to(['Kupload', 'action'=>'uploadJson']),  // 指定上传文件的服务器端程序
            'fileManagerJson' => Url::to(['Kupload', 'action'=>'fileManagerJson']),  // 指定浏览远程图片的服务器端程序
            'width' => 700,
            'height' => 300
        ];
        $this->editorOptions = array_merge($_options, $this->editorOptions);
    }

    public function run()
    {
        switch($this->editorType){
            case 'colorPicker': $html = self::renderHtml($this->id . '_colorPicker', '打开取色器'); break;
            case 'fileDialog': $html = self::renderHtml($this->id . '_fileDialog', '选择文件'); break;
            case 'imageDialog': $html = self::renderHtml($this->id . '_imageDialog', '选择图片'); break;
            case 'remoteImageDialog': $html = self::renderHtml($this->id . '_remoteImageDialog', '选择图片'); break;
            case 'localImageDialog': $html = self::renderHtml($this->id . '_localImageDialog', '选择图片'); break;
            case 'imageManager': $html = self::renderHtml($this->id . '_imageManager', '浏览服务器'); break;
            case 'flashManager': $html = self::renderHtml($this->id . '_flashManager', '浏览服务器'); break;
            case 'mediaManager': $html = self::renderHtml($this->id . '_mediaManager', '浏览服务器'); break;
            case 'fileManager': $html = self::renderHtml($this->id . '_fileManager', '浏览服务器'); break;
            //case 'uploadButton': break;
            //case 'multiImageDialog': break;
            default: $html = $this->hasModel() ? Html::activeTextarea($this->model, $this->attribute, ['id'=>$this->id]) : Html::textarea(empty($this->name) ? $this->id : $this->name, $this->value, ['id'=>$this->id]); break;
        }

        self::registerEditorScript();  // 放在最后, 是为了可以自定义 $this->buttonOptions['id']
        return $html;
    }

    /**
     * 单独调用插件时, 渲染的 HTML.
     * @param string $buttonID button 按钮的ID
     * @param string $buttonLabel button 按钮的文本内容(不是 label 属性)
     * @return mixed 渲染的 HTML.
     */
    protected function renderHtml($buttonID, $buttonLabel)
    {
        $this->buttonOptions = array_merge(['id' => $buttonID], $this->buttonOptions);
        $buttonLabel = ArrayHelper::remove($this->buttonOptions, 'label', $buttonLabel);
        if($this->hasModel()){
            return str_replace(['{input}', '{button}'], [Html::activeTextInput($this->model, $this->attribute, $this->inputOptions), Html::button($buttonLabel, $this->buttonOptions)], $this->editorTemplate);
        }else{
            return str_replace(['{input}', '{button}'], [Html::textInput(empty($this->name) ? $this->id : $this->name, $this->value, $this->inputOptions), Html::button($buttonLabel, $this->buttonOptions)], $this->editorTemplate);
        }
    }

    /**
     * 注册客户端脚本
     */
    protected function registerEditorScript()
    {
        KindEditorAsset::register($this->view);
        switch($this->editorType){
            case 'colorPicker':
                $script = <<<EOT
KindEditor.ready(function(K){
    var colorpicker;
    K('#{$this->buttonOptions['id']}').bind('click', function(e){
        e.stopPropagation();
        if(colorpicker){
            colorpicker.remove();
            colorpicker = null;
            return;
        }
        var colorpickerPos = K('#{$this->buttonOptions['id']}').pos();
        colorpicker = K.colorpicker({
            x: colorpickerPos.x,
            y: colorpickerPos.y + K('#{$this->buttonOptions['id']}').height(),
            z: 19811214,
            selectedColor: 'default',
            noColor: '无颜色',
            click: function(color){
                K('#{$this->id}').val(color);
                colorpicker.remove();
                colorpicker = null;
            }
        });
    });
    K(document).click(function(){
        if(colorpicker){
            colorpicker.remove();
            colorpicker = null;
        }
    });
});
EOT;
                break;
            case 'fileDialog':
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({
        allowFileManager: true,
        uploadJson: "{$this->editorOptions['uploadJson']}",
        fileManagerJson: "{$this->editorOptions['fileManagerJson']}"
    });
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('insertfile', function(){
            editor.plugin.fileDialog({
                fileUrl: K('#{$this->id}').val(),
                clickFn: function(url, title){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'imageDialog':
                $dialogOptions = [
                    'allowFileManager' => true,
                    'uploadJson' => $this->editorOptions['uploadJson'],
                    'fileManagerJson' => $this->editorOptions['fileManagerJson'],
                ];
                if($this->dialogImage){
                    $dialogOptions['extraFileUploadParams'] = ['dialogImage' => true];
                }
                $dialogJson = Json::encode($dialogOptions);
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({$dialogJson});
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                imageUrl: K('#{$this->id}').val(),
                clickFn: function(url, title, width, height, border, align){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'remoteImageDialog':
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({
        allowFileManager: true,
        fileManagerJson: "{$this->editorOptions['fileManagerJson']}"
    });
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                showLocal: false,
                imageUrl: K('#{$this->id}').val(),
                clickFn: function(url, title, width, height, border, align){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'localImageDialog':
                $dialogOptions = [
                    'uploadJson' => $this->editorOptions['uploadJson'],
                ];
                if($this->dialogImage){
                    $dialogOptions['extraFileUploadParams'] = ['dialogImage' => true];
                }
                $dialogJson = Json::encode($dialogOptions);
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({$dialogJson});
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                showRemote: false,
                imageUrl: K('#{$this->id}').val(),
                clickFn: function(url, title, width, height, border, align){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'imageManager':
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({
        fileManagerJson: "{$this->editorOptions['fileManagerJson']}"
    });
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('filemanager', function(){
            editor.plugin.filemanagerDialog({
                viewType: 'VIEW',
                dirName: 'image',
                clickFn: function(url, title){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'flashManager':
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({
        fileManagerJson: "{$this->editorOptions['fileManagerJson']}"
    });
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('filemanager', function(){
            editor.plugin.filemanagerDialog({
                viewType: 'LIST',
                dirName: 'flash',
                clickFn: function(url, title){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'mediaManager':
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({
        fileManagerJson: "{$this->editorOptions['fileManagerJson']}"
    });
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('filemanager', function(){
            editor.plugin.filemanagerDialog({
                viewType: 'LIST',
                dirName: 'media',
                clickFn: function(url, title){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            case 'fileManager':
                $script = <<<EOT
KindEditor.ready(function(K){
    var editor = K.editor({
        fileManagerJson: "{$this->editorOptions['fileManagerJson']}"
    });
    K('#{$this->buttonOptions['id']}').click(function(){
        editor.loadPlugin('filemanager', function(){
            editor.plugin.filemanagerDialog({
                viewType: 'LIST',
                dirName: 'file',
                clickFn: function(url, title){
                    K('#{$this->id}').val(url);
                    editor.hideDialog();
                }
            });
        });
    });
});
EOT;
                break;
            //case 'uploadButton': break;
            //case 'multiImageDialog': break;
            default:
                $script = "KindEditor.ready(function(K){K.create('#{$this->id}', " . Json::encode($this->editorOptions) . ")});";
                break;
        }
        $this->view->registerJs($script);
    }
}
