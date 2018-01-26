<?php

namespace core\db\manage\validate;

use core\base\Validate;

class ManageMenuValidate extends Validate
{

    /**
     * 规则
     *
     * @var array
     */
    protected $rule = [
        'menu_name' => 'require'
    ];

    /**
     * 提示
     *
     * @var array
     */
    protected $message = [
        'menu_name.require' => '菜单名称为空'
    ];

    /**
     * 场景
     *
     * @var array
     */
    protected $scene = [
        'add' => [
            'menu_name'
        ],
        'edit' => [
            'menu_name'
        ]
    ];

}