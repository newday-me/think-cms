<?php
namespace core\blog\validate;

use core\Validate;

class ArticleCateValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'cate_name' => 'require',
        'cate_title' => 'require',
        'cate_info' => 'require'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'cate_name.require' => '分类标识为空',
        'cate_title.require' => '分类名称为空',
        'cate_info.require' => '分类描述为空'
    ];

    /**
     * 场景
     *
     * @var unknown
     */
    protected $scene = [
        'add' => [
            'cate_name',
            'cate_title',
            'cate_info'
        ],
        'edit' => [
            'cate_name',
            'cate_title',
            'cate_info'
        ]
    ];
}