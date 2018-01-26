<?php

namespace core\db\manage\validate;

use core\base\Validate;

class ManageUserValidate extends Validate
{

    /**
     * 规则
     *
     * @var array
     */
    protected $rule = [
        'user_name' => 'require',
        'user_nick' => 'require',
        'user_password' => 'require'
    ];

    /**
     * 提示
     *
     * @var array
     */
    protected $message = [
        'user_name.require' => '用户名为空',
        'user_nick.require' => '昵称为空',
        'user_password.require' => '密码为空'
    ];

    /**
     * 场景
     *
     * @var array
     */
    protected $scene = [
        'add' => [
            'user_name',
            'user_nick',
            'user_password'
        ],
        'edit' => [
            'user_name',
            'user_nick'
        ],
        'login' => [
            'user_name',
            'user_password'
        ],
        'account' => [
            'user_nick'
        ]
    ];

}