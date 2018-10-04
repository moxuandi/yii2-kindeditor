<?php
/**
 * 前后端通信相关的配置
 * see[参考] http://fex.baidu.com/ueditor/#server-config
 */
return [
    /* 上传图片{image}配置项 */
    'imageMaxSize' => 5*1024*1024,  // 上传大小限制, 单位B, 默认5MB, 注意修改服务器的大小限制
    'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 上传图片格式显示
    'thumbStatus' => true,  // 是否生成缩略图
    'thumbWidth' => 300,  // 缩略图宽度
    'thumbHeight' => 200,  // 缩略图高度
    'thumbCut' => 1,  // 生成缩略图的方式, 0:留白, 1:裁剪
    'imagePathFormat' => 'uploads/image/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 上传保存路径,可以自定义保存路径和文件名格式
    /* {filename} 会替换成原文件名[要注意中文文件乱码问题] */
    /* {rand:6} 会替换成随机数, 后面的数字是随机数的位数 */
    /* {time} 会替换成时间戳 */
    /* {yyyy} 会替换成四位年份 */
    /* {yy} 会替换成两位年份 */
    /* {mm} 会替换成两位月份 */
    /* {dd} 会替换成两位日期 */
    /* {hh} 会替换成两位小时 */
    /* {ii} 会替换成两位分钟 */
    /* {ss} 会替换成两位秒 */
    /* 非法字符 \ : * ? " < > | */
    /* 具请体看线上文档: http://fex.baidu.com/ueditor/#server-path 3.1 */


    /* 上传{flash}配置项 */
    'flashMaxSize' => 20*1024*1024,  // 上传大小限制, 单位B, 默认20MB, 注意修改服务器的大小限制
    'flashAllowFiles' => ['.flv', '.swf'],  // 上传动画格式显示
    'flashPathFormat' => 'uploads/flash/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 上传保存路径


    /* 上传{media}配置项 */
    'mediaMaxSize' => 20*1024*1024,  // 上传大小限制, 单位B, 默认20MB, 注意修改服务器的大小限制
    'mediaPathFormat' => 'uploads/media/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 上传保存路径
    'mediaAllowFiles' => ['.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'],  // 上传视频格式显示


    /* 上传文件{file}配置项 */
    'fileMaxSize' => 50*1024*1024,  // 上传大小限制, 单位B, 默认50MB, 注意修改服务器的大小限制
    'filePathFormat' => 'uploads/file/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 上传保存路径
    'fileAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid', '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],  // 上传文件格式显示

    // 保存上传信息到数据库, 使用前请导入'database'文件夹中的数据表'upload'和模型类'Upload'
    'saveDatabase' => false,
];