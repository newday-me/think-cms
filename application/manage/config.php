<?php
return [
    // 异常处理
    'exception_handle' => 'app\manage\custom\exception\Handle',
    
    // 分页
    'paginate' => [
        'type' => 'cms\paginator\AmazeUi',
        'var_page' => 'page',
        'list_rows' => 10
    ]
];