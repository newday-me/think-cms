<?php
namespace core\blog\validate;

use core\Validate;

class PageValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'page_title' => 'require',
        'page_name' => 'require',
        'page_content' => 'require'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'page_title.require' => '页面标题为空',
        'page_name.require' => '页面名称为空',
        'page_content.require' => '页面内容为空'
    ];

    /**
     * 场景
     *
     * @var unknown
     */
    protected $scene = [
        'add' => [
            'page_title',
            'page_name',
            'page_content'
        ],
        'edit' => [
            'page_title',
            'page_name',
            'page_content'
        ]
    ];
}