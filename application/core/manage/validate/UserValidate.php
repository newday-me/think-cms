<?php
namespace core\manage\validate;

use core\Validate;

class UserValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'user_name' => 'require',
        'user_nick' => 'require',
        'user_passwd' => 'require',
        'user_passwd_confirm' => 'require|confirm:user_passwd',
        'user_gid' => 'require'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'user_name.require' => '用户名为空',
        'user_nick.require' => '昵称为空',
        'user_passwd.require' => '密码为空',
        'user_passwd_confirm.require' => '重复密码为空',
        'user_passwd_confirm.confirm' => '两次密码不同',
        'user_gid.require' => '分组ID不能为空'
    ];

    /**
     * 场景
     *
     * @var unknown
     */
    protected $scene = [
        'login' => [
            'user_name',
            'user_passwd'
        ],
        'add' => [
            'user_name',
            'user_passwd',
            'user_passwd_confirm',
            'user_gid'
        ],
        'edit_info' => [
            'user_name',
            'user_nick',
            'user_gid'
        ],
        'edit_passwd' => [
            'user_name',
            'user_nick',
            'user_passwd',
            'user_passwd_confirm',
            'user_gid'
        ],
        'install' => [
            'user_name',
            'user_nick',
            'user_passwd',
            'user_passwd_confirm'
        ]
    ];
}