[富文本编辑器 KindEditor for Yii2](http://kindeditor.net)
================
KindEditor 是一套开源的在线HTML编辑器，主要用于让用户在网站上获得所见即所得编辑效果，开发人员可以用 KindEditor 把传统的多行文本输入框(textarea)替换为可视化的富文本输入框。
KindEditor 使用 JavaScript 编写，可以无缝地与 Java、.NET、PHP、ASP 等程序集成，比较适合在 CMS、商城、论坛、博客、Wiki、电子邮件等互联网应用上使用。


安装:
------------
使用 [composer](http://getcomposer.org/download/) 下载:
```
# 2.x(yii >= 2.0.16):
composer require moxuandi/yii2-kindeditor:"~2.1.0"
composer require moxuandi/yii2-kindeditor:"~2.0.0"

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
            'class' => 'moxuandi\kindeditor\UploaderAction',
            //可选参数, 参考 config.php
            'config' => [
                'imageMaxSize' => 1*1024*1024,  // 上传大小限制, 单位B, 默认1MB, 注意修改服务器的大小限制
                'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 允许上传的文件类型
                'imagePathFormat' => '/uploads/image/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径

                // 如果`uploads`目录与当前应用的入口文件不在同一个目录, 必须做如下配置:
                'rootPath' => dirname(dirname(Yii::$app->request->scriptFile)),
                'rootUrl' => 'http://image.advanced.ccc',
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
    'editorOptions' => ['width' => '1000', 'height' => 500],
]);

3. 不带 $model 调用:
\moxuandi\kindeditor\KindEditor::widget([
    'name' => 'image',
    'editorOptions' => ['width' => '1000', 'height' => 500],
]);
```

编辑器相关配置，请在`view`中配置，参数为`editorOptions`，比如定制菜单，编辑器大小等等，具体参数请查看[KindEditor官网文档](http://kindeditor.net/docs/option.html)


#### 单独调用插件:
```php
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'imageDialog',
    'options' => [  // input输入域的html属性
        'class' => 'form-control',
        'style' => 'display:inline-block;width:calc(100% - 84px);margin-right:6px;',
    ],
    'buttonOptions' => [  // 按钮的html属性
        'class' => 'btn btn-default',
    ],
]);

\moxuandi\kindeditor\KindEditor::widget([
    'name' => 'image',
    'editorType' => 'imageDialog',
    'options' => [  // input输入域的html属性
        'class' => 'form-control',
        'style' => 'display:inline-block;width:calc(100% - 84px);margin-right:6px;'
    ],
    'buttonOptions' => [  // 按钮的html属性
        'class' => 'btn btn-default'
    ],
]);
```

#### 同时调用编辑器和独立插件，并且图片/文件上传不一样时：
```php
Controller:
public function actions()
{
    return [
        'Kupload' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
        ],
        'Kupload2' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
            //可选参数, 参考 config.php
            'config' => [
                 // 生成缩略图
                'thumb' => [
                    'width' => 150,  // 缩略图宽度
                    'height' => 100, // 缩略图高度
                ],
            ],
        ],
        'Kupload3' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
            //可选参数, 参考 config.php
            'config' => [
                'filePathFormat' => '/uploads/file/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
                'fileRootPath' => '/uploads/file/',  // 浏览服务器时的根目录
            ],
        ],
    ];
}

view:
1. 编辑器(不生成缩略图)
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor');
2. 图片上传(生成缩略图)
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'imageDialog',
    'editorOptions' => [
        'uploadJson' => Url::to(['Kupload2', 'action'=>'uploadJson']),  // 指定上传文件的服务器端程序
        'fileManagerJson' => Url::to(['Kupload2', 'action'=>'fileManagerJson']),  // 指定浏览远程图片的服务器端程序
    ],
]);
3. 文件上传
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'fileDialog',
    'editorOptions' => [
        'uploadJson' => Url::to(['Kupload3', 'action'=>'uploadJson']),  // 指定上传文件的服务器端程序
        'fileManagerJson' => Url::to(['Kupload3', 'action'=>'fileManagerJson']),  // 指定浏览远程图片的服务器端程序
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

## 可视化图片上传:
```
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditorImage', [
    'editorType' => 'imageDialog',
]);

\moxuandi\kindeditor\KindEditorImage::widget([
    'name' => 'image',
    'value' => '/uploads/image/20190216/111632_184320.jpg',
    'editorType' => 'remoteImageDialog',
]);
```

另可配置缩略图,裁剪图,水印等, 对图片做进一步处理; 详细配置请参考[moxuandi\helpers\Uploader](https://github.com/moxuandi/yii2-helpers)
```php
'config' => [
    // 缩略图
    'thumb' => [
        'width' => 300,
        'height' => 200,
        //'mode' => 'outbound',  // 'inset'(补白), 'outbound'(裁剪, 默认值)
        //'match' => ['image', 'thumb'],
    ],

    // 裁剪图像
    'crop' => [
        'width' => 300,
        'height' => 200,
        //'top' => 0,
        //'left' => 0,
        //'match' => ['image', 'crop'],
    ],

    // 添加边框
    'frame' => [
        'margin' => 20,
        //'color' => '666',
        //'alpha' => 100,
        //'match' => ['image', 'frame'],
    ],

    // 添加图片水印
    'watermark' => [
        'watermarkImage' => '@web/uploads/watermark.png',
        //'top' => 0,
        //'left' => 0,
        //'match' => ['image', 'watermark'],
    ],

    // 添加文字水印
    'text' => [
        'text' => '水印文字',
        'fontFile' => '@web/uploads/simhei.ttf',  // 字体文件的位置
        /*'fontOptions' => [
            'size' => 12,
            'color' => 'fff',
            'angle' => 0,
        ],*/
        //'top' => 0,
        //'left' => 0,
        //'match' => ['image', 'text'],
    ],

    // 调整图片大小
    'resize' => [
        'width' => 300,
        'height' => 200,
        //'keepAspectRatio' => true,  // 是否保持图片纵横比
        //'allowUpscaling' => false,  // 如果原图很小, 图片是否放大
        //'match' => ['image', 'resize'],
    ],
],
```
