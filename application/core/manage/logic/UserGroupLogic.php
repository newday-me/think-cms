<?php
namespace core\manage\logic;

use core\Logic;
use core\manage\model\UserGroupModel;

class UserGroupLogic extends Logic
{

    /**
     * 获取群组列表
     *
     * @return array
     */
    public function getGroupList()
    {
        $model = UserGroupModel::getSingleton();
        $list = $model->select();
        
        $groups = [];
        foreach ($list as $vo) {
            $groups[] = [
                'name' => $vo['group_name'],
                'value' => $vo['id']
            ];
        }
        return $groups;
    }

    /**
     * 群组菜单
     *
     * @param number $groupId            
     * @return array
     */
    public function getGroupMenuIds($groupId)
    {
        $map = [
            'id' => $groupId
        ];
        $model = UserGroupModel::getSingleton();
        $group = $model->field('group_menus')
            ->where($map)
            ->find();
        if ($group && $group['group_menus']) {
            return explode(',', $group['group_menus']);
        } else {
            return [];
        }
    }
}