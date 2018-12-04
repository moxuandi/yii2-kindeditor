[富文本编辑器 KindEditor for Yii2](http://kindeditor.net)
================
KindEditor 是一套开源的在线HTML编辑器，主要用于让用户在网站上获得所见即所得编辑效果，开发人员可以用 KindEditor 把传统的多行文本输入框(textarea)替换为可视化的富文本输入框。
KindEditor 使用 JavaScript 编写，可以无缝地与 Java、.NET、PHP、ASP 等程序集成，比较适合在 CMS、商城、论坛、博客、Wiki、电子邮件等互联网应用上使用。


安装:
------------
使用 [composer](http://getcomposer.org/download/) 下载:
```
# 2.x(yii >= 2.0.16):
composer require moxuandi/yii2-kindeditor:"~2.0"

# 1.x(非重要Bug, 不再更新):
composer require moxuandi/yii2-kindeditor:"~1.0"

# 旧版归档(不再更新):
composer require moxuandi/yii2-kindeditor:"~0.1"

# 开发版:
composer require moxuandi/yii2-kindeditor:"dev-master"
```


使用:
-----
在`Controller`中添加:
```php
public function actions()
{
    return [
        'Kupload' => [
            'class' => 'moxuandi\kindeditor\KindEditorAction',
            //可选参数, 参考 config.php
            'config' => [
                'thumbWidth' => 150,    // 缩略图宽度
                'thumbHeight' => 100,   // 缩略图高度
                'saveDatabase' => true,  // 保存上传信息到数据库
                    // 使用前请导入'database'文件夹中的数据表'upload'和模型类'Upload'
            ],
        ],
    ];
}
```

在`View`中添加:
```php
1. 简单调用:
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor');

2. 带参数调用:
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor',[
    'clientOptions' => [
        'width' => '1000',
        'height' => 500,
    ],
]);

3. 不带 $model 调用:
\moxuandi\kindeditor\KindEditor::widget([
    'clientOptions' => [
        'width' => '1000',
        'height' => 500,
    ],
]);
```

编辑器相关配置，请在`view`中配置，参数为`clientOptions`，比如定制菜单，编辑器大小等等，具体参数请查看[KindEditor官网文档](http://kindeditor.net/docs/option.html)


#### 单独调用插件(model版):
```php
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'imageDialog',
    'inputOptions' => [  //input输入域的html属性
        'style' => 'display:inline-block;width:calc(100% - 84px);margin-right:6px;'
    ],
    'buttonOptions' => [  //按钮的html属性
        'class' => 'btn btn-default',
    ],
]);
```

#### 单独调用插件(无model版):
```php
\moxuandi\kindeditor\KindEditor::widget([
    'editorType' => 'imageDialog',
    'inputOptions' => [  //input输入域的html属性
        'style' => 'display:inline-block;width:calc(100% - 84px);margin-right:6px;'
    ],
    'buttonOptions' => [  //按钮的html属性
        'class' => 'btn btn-default'
    ],
]);
```

editorType: 定义编辑器的类型, 值有：
```php
     textEditor: HTML编辑器(默认)
     colorPicker: 取色器
     uploadButton: 自定义上传按钮
     fileDialog: 上传文件
     imageDialog: 上传图片(网络图片 + 本地上传)
     RemoteImageDialog: 上传图片(网络图片)
     LocalImageDialog: 上传图片(本地上传)
     imageManager: 浏览服务器(图片)
     flashManager: 浏览服务器(Flash)
     mediaManager: 浏览服务器(视音频)
     fileManager: 浏览服务器(文件)
     multiImageDialog: 批量上传图片(未实现)
```


`KindEditorUpload::actionList()`方法考虑使用以下方式响应:
```
$response = Yii::$app->response;
$response->format = Response::FORMAT_JSON;
$response->data = [
    'moveup_dir_path' => $moveupDirPath,    // 相对于根目录的上一级目录
    'current_dir_path' => $currentDirPath,  // 相对于根目录的当前目录
    'current_url' => $currentUrl,           // 当前目录的URL
    'total_count' => count($fileList),      // 文件总数
    'file_list' => $fileList                // 文件列表
];
$response->send();
```
