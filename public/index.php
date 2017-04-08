<?php

// WEB目录
define('WEB_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// 跟目录
define('ROOT_PATH', dirname(WEB_PATH) . DIRECTORY_SEPARATOR);

// 应用目录
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);

// 框架引导文件
require ROOT_PATH . 'thinkphp' . DIRECTORY_SEPARATOR . 'start.php';