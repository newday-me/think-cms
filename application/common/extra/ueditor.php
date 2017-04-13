<?php
return [
    
    // 执行上传图片的action名称
    'imageActionName' => 'uploadimage',
    // 提交的图片表单名称
    'imageFieldName' => 'upfile',
    // 上传大小限制，单位B
    'imageMaxSize' => 2048000,
    // 允许上传图片的后缀名
    'imageAllowFiles' => [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp'
    ],
    
    // 是否压缩图片,默认是true
    'imageCompressEnable' => true,
    // 图片压缩最长边限制
    'imageCompressBorder' => 1600,
    // 插入的图片浮动方式
    'imageInsertAlign' => 'none',
    // 图片访问路径前缀
    'imageUrlPrefix' => '',
    // 图片访问路径格式
    'imagePathFormat' => '/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
    
    // 执行上传涂鸦的action名称
    'scrawlActionName' => 'uploadscrawl',
    // 提交的图片表单名称
    'scrawlFieldName' => 'upfile',
    // 上传保存路径,可以自定义保存路径和文件名格式
    'scrawlPathFormat' => '/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
    // 上传大小限制，单位B
    'scrawlMaxSize' => 2048000,
    // 图片访问路径前缀
    'scrawlUrlPrefix' => '',
    // 插入的图片浮动方式
    'scrawlInsertAlign' => 'none',
    
    // 执行上传截图的action名称
    'snapscreenActionName' => 'uploadimage',
    // 上传保存路径,可以自定义保存路径和文件名格式
    'snapscreenPathFormat' => '/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
    // 图片访问路径前缀
    'snapscreenUrlPrefix' => '',
    // 插入的图片浮动方式
    'snapscreenInsertAlign' => 'none',
    
    // 本地图片域名
    'catcherLocalDomain' => [
        '127.0.0.1',
        'localhost',
        'img.baidu.com'
    ],
    // 执行抓取远程图片的action名称
    'catcherActionName' => 'catchimage',
    // 提交的图片列表表单名称
    'catcherFieldName' => 'source',
    // 上传保存路径,可以自定义保存路径和文件名格式
    'catcherPathFormat' => '/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
    // 图片访问路径前缀
    'catcherUrlPrefix' => '',
    // 上传大小限制，单位B
    'catcherMaxSize' => 2048000,
    // 允许抓取的图片后缀名
    'catcherAllowFiles' => [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp'
    ],
    
    // 执行上传视频的action名称
    'videoActionName' => 'uploadvideo',
    // 提交的视频表单名称
    'videoFieldName' => 'upfile',
    // 上传保存路径,可以自定义保存路径和文件名格式
    'videoPathFormat' => '/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}',
    // 视频访问路径前缀
    'videoUrlPrefix' => '',
    // 上传大小限制，单位B，默认100MB
    'videoMaxSize' => 102400000,
    // 允许上传视频后缀名
    'videoAllowFiles' => [
        '.flv',
        '.swf',
        '.mkv',
        '.avi',
        '.rm',
        '.rmvb',
        '.mpeg',
        '.mpg',
        '.ogg',
        '.ogv',
        '.mov',
        '.wmv',
        '.mp4',
        '.webm',
        '.mp3',
        '.wav',
        '.mid'
    ],
    
    // 上传文件的action名称
    'fileActionName' => 'uploadfile',
    // 提交的文件表单名称
    'fileFieldName' => 'upfile',
    // 上传保存路径,可以自定义保存路径和文件名格式
    'filePathFormat' => '/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}',
    // 文件访问路径前缀
    'fileUrlPrefix' => '',
    // 上传大小限制，单位B，默认50MB
    'fileMaxSize' => 51200000,
    // 允许上传的文件后缀名
    'fileAllowFiles' => [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp',
        '.flv',
        '.swf',
        '.mkv',
        '.avi',
        '.rm',
        '.rmvb',
        '.mpeg',
        '.mpg',
        '.ogg',
        '.ogv',
        '.mov',
        '.wmv',
        '.mp4',
        '.webm',
        '.mp3',
        '.wav',
        '.mid',
        '.rar',
        '.zip',
        '.tar',
        '.gz',
        '.7z',
        '.bz2',
        '.cab',
        '.iso',
        '.doc',
        '.docx',
        '.xls',
        '.xlsx',
        '.ppt',
        '.pptx',
        '.pdf',
        '.txt',
        '.md',
        '.xml'
    ],
    
    // 执行图片管理的action名称
    'imageManagerActionName' => 'listimage',
    // 指定要列出图片的目录
    'imageManagerListPath' => '/ueditor/php/upload/image/',
    // 每次列出文件数量
    'imageManagerListSize' => 20,
    // 图片访问路径前缀
    'imageManagerUrlPrefix' => '',
    // 插入的图片浮动方式
    'imageManagerInsertAlign' => 'none',
    // 允许列出的图片后缀名
    'imageManagerAllowFiles' => [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp'
    ],
    
    // 执行文件管理的action名称
    'fileManagerActionName' => 'listfile',
    // 指定要列出文件的目录
    'fileManagerListPath' => '/ueditor/php/upload/file/',
    // 文件访问路径前缀
    'fileManagerUrlPrefix' => '',
    // 每次列出文件数量
    'fileManagerListSize' => 20,
    // 允许列出的文件后缀名
    'fileManagerAllowFiles' => [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp',
        '.flv',
        '.swf',
        '.mkv',
        '.avi',
        '.rm',
        '.rmvb',
        '.mpeg',
        '.mpg',
        '.ogg',
        '.ogv',
        '.mov',
        '.wmv',
        '.mp4',
        '.webm',
        '.mp3',
        '.wav',
        '.mid',
        '.rar',
        '.zip',
        '.tar',
        '.gz',
        '.7z',
        '.bz2',
        '.cab',
        '.iso',
        '.doc',
        '.docx',
        '.xls',
        '.xlsx',
        '.ppt',
        '.pptx',
        '.pdf',
        '.txt',
        '.md',
        '.xml'
    ]
];