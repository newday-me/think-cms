<?php
namespace core\manage\model;

use core\Model;

class ConfigGroupModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_config_group';

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
        return $this->field('group_name as name, id as value')
            ->order('group_sort desc')
            ->select();
    }
}