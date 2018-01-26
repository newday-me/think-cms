<?php

namespace core\db\manage\validate;


use core\base\Validate;

class ManageUserGroupValidate extends Validate
{
    /**
     * 规则
     *
     * @var array
     */
    protected $rule = [
        'group_name' => 'require'
    ];

    /**
     * 提示
     *
     * @var array
     */
    protected $message = [
        'group_name.require' => '群组名称为空'
    ];

    /**
     * 场景
     *
     * @var array
     */
    protected $scene = [
        'add' => [
            'group_name'
        ],
        'edit' => [
            'group_name'
        ]
    ];
}