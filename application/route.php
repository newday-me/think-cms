<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [
    '__pattern__' => [
        'name' => '\w+'
    ],
    
    // 主页
    'download' => 'index/index/download',
    
    // 博客
    'home' => 'blog/index/index',
    'cate/:name' => 'blog/index/cate',
    'article/:key' => 'blog/index/show',
    
    // 后台
    'module/:_module_/:_controller_/:_action_' => 'manage/loader/run'
];