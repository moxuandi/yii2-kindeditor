<?php
/**
 * 前后端通信相关的配置
 * see [参考](http://fex.baidu.com/ueditor/#server-config)
 */
return [
    /* 上传图片{image}配置项 */
    'imageMaxSize' => 1*1024*1024,  // 上传大小限制, 单位B, 默认1MB, 注意修改服务器的大小限制
    'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 允许上传的文件类型
    'imagePathFormat' => '/uploads/image/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 文件保存路径
    'thumbStatus' => false,  // 是否生成缩略图
    'thumbWidth' => 300,  // 缩略图的宽度
    'thumbHeight' => 200,  // 缩略图的高度
    'thumbMode' => 'outbound',  // 生成缩略图的模式, 可用值: 'inset'(补白), 'outbound'(裁剪, 默认值).
    'imageRootPath' => '/uploads/image/',  // 浏览服务器时的根目录

    /* 仅在单独调用`imageDialog`和`localImageDialog`插件, 且`dialogImage=true`时生效: */
    'dialogImageMaxSize' => 1*1024*1024,  // 上传大小限制, 单位B, 默认1MB, 注意修改服务器的大小限制
    'dialogImageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 允许上传的文件类型
    'dialogImagePathFormat' => '/uploads/image/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 文件保存路径
    'dialogThumbStatus' => true,  // 是否生成缩略图
    'dialogThumbWidth' => 150,  // 缩略图的宽度
    'dialogThumbHeight' => 100,  // 缩略图的高度
    'dialogThumbMode' => 'outbound',  // 生成缩略图的模式, 可用值: 'inset'(补白), 'outbound'(裁剪, 默认值).


    /* 上传{flash}配置项 */
    'flashMaxSize' => 5*1024*1024,  // 上传大小限制, 单位B, 默认5MB, 注意修改服务器的大小限制
    'flashAllowFiles' => ['.flv', '.swf'],  // 允许上传的文件类型
    'flashPathFormat' => '/uploads/flash/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 文件保存路径
    'flashRootPath' => '/uploads/flash/',  // 浏览服务器时的根目录


    /* 上传{media}配置项 */
    'mediaMaxSize' => 10*1024*1024,  // 上传大小限制, 单位B, 默认10MB, 注意修改服务器的大小限制
    'mediaAllowFiles' => ['.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'],  // 允许上传的文件类型
    'mediaPathFormat' => '/uploads/media/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 文件保存路径
    'mediaRootPath' => '/uploads/media/',  // 浏览服务器时的根目录


    /* 上传文件{file}配置项 */
    'fileMaxSize' => 10*1024*1024,  // 上传大小限制, 单位B, 默认10MB, 注意修改服务器的大小限制
    'fileAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid', '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],  // 允许上传的文件类型
    'filePathFormat' => '/uploads/file/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 文件保存路径
    'fileRootPath' => '/uploads/file/',  // 浏览服务器时的根目录
];
