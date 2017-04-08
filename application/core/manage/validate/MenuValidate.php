<?php
namespace core\manage\validate;

use core\Validate;

class MenuValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'menu_name' => 'require',
        'menu_url' => 'require',
        'menu_pid' => 'require'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'menu_name.require' => '菜单名称为空',
        'menu_url.require' => '菜单链接为空',
        'menu_pid.require' => '上级菜单为空'
    ];

    /**
     * 场景
     *
     * @var unknown
     */
    protected $scene = [
        'add' => [
            'menu_name',
            'menu_pid'
        ],
        'edit' => [
            'menu_name',
            'menu_pid'
        ]
    ];
}