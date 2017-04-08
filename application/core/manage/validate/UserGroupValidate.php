<?php
namespace core\manage\validate;

use core\Validate;

class UserGroupValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'group_name' => 'require',
        'group_menus' => 'require'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'group_name.require' => '分组名称为空',
        'group_menus.require' => '用户组允许访问的菜单为空'
    ];

    /**
     * 场景
     *
     * @var unknown
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