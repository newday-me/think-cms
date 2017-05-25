<?php
namespace core\manage\logic;

use core\Logic;
use core\manage\model\ConfigGroupModel;

class ConfigGroupLogic extends Logic
{

    /**
     * 获取列表下拉
     *
     * @return array
     */
    public function getSelectList()
    {
        return ConfigGroupModel::getInstance()->field('id as value, group_name as name')
            ->order('group_sort desc')
            ->select();
    }

}