<?php
// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require dirname(__DIR__) . '/thinkphp/base.php';

// 执行应用并响应
Container::get('app')->run()->send();