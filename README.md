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


用法示例:
-----
在`Controller`中添加:
```php
public function actions()
{
    return [
        'Kupload' => [
            'class' => 'moxuandi\kindeditor\KindEditorUpload',
            // 可选参数, 参考 config.php
            'config' => [
                'imageMaxSize' => 1*1024*1024,
                'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
                'imagePathFormat' => '/uploads/image/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',
                'thumbStatus' => false,
                'thumbWidth' => 300,
                'thumbHeight' => 200,
                'thumbMode' => 'outbound',
                'imageRootPath' => '/uploads/image/',
                'rootPath' => dirname(Yii::$app->request->scriptFile),  // 入口文件目录
            ],
        ],
    ];
}
```

在`View`中添加:
```php
// 1. 简单调用(基于模型):
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor');

// 2. 带参数调用(此模式下仅`$editorOptions`参数可用):
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor', [
    'editorOptions' => [
        'width' => 1000,
        'height' => 500,
    ],
]);

// 3. 不带`$model`调用:
\moxuandi\kindeditor\KindEditor::widget([
    'id' => 'editor',
    'attribute' => 'content',
    //'name' => 'content',
    'value' => '初始化编辑器时的内容',
    'editorOptions' => [
        'width' => 1000,
        'height' => 500,
    ],
]);
```

编辑器相关配置，请在`view`中配置，参数为`editorOptions`，比如定制菜单，编辑器大小等等，具体参数请查看[KindEditor官网文档](http://kindeditor.net/docs/option.html)


#### 在`View`中单独调用插件:
```php
// 1. 基于模型:
$form->field($model, 'file')->widget('moxuandi\kindeditor\KindEditor', ['editorType' => 'fileDialog']);

// 2. 带参数调用(已列出所有可用参数, 可适当忽略):
$form->field($model, 'file')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'fileDialog',
    'editorTemplate' => "<div class='input-group'>{input}<span class='input-group-btn'>{button}</span></div>",
    'inputOptions' => [
        'class' => 'form-control',
    ],
    'buttonOptions' => [
        'class' => 'btn btn-default',
        'label' => '选择文件',
    ],
]);

// 3. 不带`$model`调用(已列出所有可用参数, 可适当忽略):
\moxuandi\kindeditor\KindEditor::widget([
    'editorType' => 'fileManager',
    'editorTemplate' => "<div class='input-group'>{input}<span class='input-group-btn'>{button}</span></div>",
    'inputOptions' => [
        'class' => 'form-control',
    ],
    'buttonOptions' => [
        'class' => 'btn btn-default',
        'label' => '选择文件',
    ],
    'id' => 'editor',
    'attribute' => 'content',
    //'name' => 'content',
    'value' => 'file.zip',
]);
```

上传根目录控制:
```php
// 前后和后台入口文件在统一位置, /web/index.php, /web/admin.php:
'rootPath' => dirname(Yii::$app->request->scriptFile),  // 前后台相同
        
// 前后和后台入口文件在不同的位置, /web/index.php, /web/admin/index.php:
'rootPath' => dirname(Yii::$app->request->scriptFile),  // 前台
'rootPath' => dirname(dirname(Yii::$app->request->scriptFile)),  // 后台
```

#### 单独调用`imageDialog`和`localImageDialog`插件时, 通过添加`dialogImage`参数, 可以独立控制缩略图大小等信息:
```php
// 控制器:
public function actions()
{
    return [
        'Kupload' => [
            'class' => 'moxuandi\kindeditor\KindEditorUpload',
            // 可选参数, 参考 config.php
            'config' => [
                'dialogImageMaxSize' => 1*1024*1024,
                'dialogImageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
                'dialogImagePathFormat' => '/uploads/image/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',
                'dialogThumbStatus' => true,
                'dialogThumbWidth' => 150,
                'dialogThumbHeight' => 100,
                'dialogThumbMode' => 'outbound',
            ],
        ],
    ];
}

// 视图:
$form->field($model, 'file')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'imageDialog',
    'dialogImage' => true,
]);
```


#### 可调用的插件类型(参数`$editorType`的值):
```
`textEditor`: HTML 编辑器(默认值)
`colorPicker`: 取色器, 无法自定义颜色(编辑器调用时正常)
`fileDialog`: 上传文件
`imageDialog`: 上传图片(网络图片 + 本地上传)
`remoteImageDialog`: 上传图片(网络图片)
`localImageDialog`: 上传图片(本地上传)
`imageManager`: 浏览服务器(图片)
`flashManager`: 浏览服务器(Flash)
`mediaManager`: 浏览服务器(视音频)
`fileManager`: 浏览服务器(文件)
`uploadButton`: 自定义上传按钮(不建议)
`multiImageDialog`: 批量上传图片(未实现)
```
