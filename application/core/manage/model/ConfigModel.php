<?php
namespace core\manage\model;

use core\Model;

class ConfigModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_config';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 获取分组列表
     *
     * @return array
     */
    public function getGroupList()
    {
        return $this->field('id, config_group')
            ->group('config_group')
            ->order('config_sort asc')
            ->select();
    }

    /**
     * 获取类型列表
     *
     * @return array
     */
    public function getTypeList()
    {
        return [
            [
                'name' => '文本',
                'value' => 'text'
            ],
            [
                'name' => '文本域',
                'value' => 'textarea'
            ],
            [
                'name' => '标签',
                'value' => 'tag'
            ],
            [
                'name' => '日期',
                'value' => 'date'
            ],
            [
                'name' => '颜色',
                'value' => 'color'
            ],
            [
                'name' => '图片',
                'value' => 'image'
            ],
            [
                'name' => '文件',
                'value' => 'file'
            ],
            [
                'name' => '多选',
                'value' => 'checkbox'
            ],
            [
                'name' => '单选',
                'value' => 'radio'
            ],
            [
                'name' => '下拉',
                'value' => 'select'
            ],
            [
                'name' => '数组',
                'value' => 'array'
            ],
            [
                'name' => '富文本',
                'value' => 'editor'
            ]
        ];
    }

}