<?php
return [
    // 根命名空间
    'root_namespace' => [
        'core' => APP_PATH . 'core',
        'module' => APP_PATH . 'module'
    ],
    
    // 禁止访问模块
    'deny_module_list' => [
        'common',
        'core',
        'module',
        'extra'
    ]
];