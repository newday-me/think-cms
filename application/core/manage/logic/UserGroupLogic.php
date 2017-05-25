<?php
namespace core\manage\logic;

use core\Logic;
use core\manage\model\UserGroupModel;

class UserGroupLogic extends Logic
{

    /**
     * 获取列表下拉
     *
     * @return array
     */
    public function getSelectList()
    {
        return UserGroupModel::getInstance()->field('id as value, group_name as name')->select();
    }

    /**
     * 获取状态下拉
     *
     * @return array
     */
    public function getSelectStatus()
    {
        return [
            [
                'name' => '启用',
                'value' => 1
            ],
            [
                'name' => '禁用',
                'value' => 0
            ]
        ];
    }

    /**
     * 群组菜单
     *
     * @param number $groupId            
     * @return array
     */
    public function getGroupMenuIds($groupId)
    {
        $group = UserGroupModel::get($groupId);
        if ($group && $group['group_menus']) {
            return explode(',', $group['group_menus']);
        } else {
            return [];
        }
    }
}