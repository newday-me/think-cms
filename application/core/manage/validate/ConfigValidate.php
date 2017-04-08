<?php
namespace core\manage\validate;

use core\Validate;

class ConfigValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'config_name' => 'require',
        'config_value' => 'require',
        'config_type' => 'require',
        'config_title' => 'require',
        'config_group' => 'require',
        'config_sort' => 'require|number'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'config_name.require' => '配置名称为空',
        'config_value.require' => '配置值为空',
        'config_type.require' => '配置类型为空',
        'config_title.require' => '配置标题为空',
        'config_group.require' => '配置分组为空',
        'config_sort.require' => '配置排序值为空',
        'config_sort.number' => '无效的排序值'
    ];

    /**
     * 场景
     *
     * @var unknown
     */
    protected $scene = [
        'add' => [
            'config_name',
            'config_type',
            'config_title',
            'config_group',
            'config_sort'
        ],
        'edit' => [
            'config_name',
            'config_type',
            'config_title',
            'config_group',
            'config_sort'
        ]
    ];
}