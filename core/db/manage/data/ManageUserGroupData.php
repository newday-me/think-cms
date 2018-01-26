<?php

namespace core\db\manage\data;

use core\base\Data;
use core\db\manage\model\ManageUserGroupModel;

class ManageUserGroupData extends Data
{

    /**
     * 创建群组
     *
     * @param array $data
     * @return array|null
     */
    public function createGroup($data)
    {
        $record = ManageUserGroupModel::create($data);
        return $this->recordToArray($record);
    }

    /**
     * 获取群组
     *
     * @param string $groupNo
     * @return array|null
     */
    public function getGroup($groupNo)
    {
        $map = [
            'group_no' => $groupNo
        ];
        $record = ManageUserGroupModel::get($map);
        return $this->recordToArray($record);
    }

    /**
     * 获取群组列表
     *
     * @return array
     */
    public function getGroupList()
    {
        $list = ManageUserGroupModel::getInstance()->order('group_sort asc')->select();
        return $this->listToArray($list);
    }

    /**
     * 获取子群组列表
     *
     * @param string $groupNo
     * @return array
     */
    public function getSubGroupList($groupNo)
    {
        $map = [
            'group_pno' => $groupNo
        ];
        $list = ManageUserGroupModel::getInstance()->where($map)->order('group_sort asc')->select();
        return $this->listToArray($list);
    }

    /**
     * 获取子群组数量
     *
     * @param string $groupNo
     * @return int|string
     */
    public function getSubGroupCount($groupNo)
    {
        $map = [
            'group_pno' => $groupNo
        ];
        return ManageUserGroupModel::getSingleton()->where($map)->count();
    }

    /**
     * 获取最大排序值
     *
     * @param string $groupNo
     * @return int
     */
    public function getMaxGroupSort($groupNo)
    {
        $map = [
            'group_pno' => $groupNo
        ];
        $menuSort = ManageUserGroupModel::getInstance()->where($map)->max('group_sort');
        return intval($menuSort);
    }

    /**
     * 更新群组
     *
     * @param string $groupNo
     * @param array $data
     * @return false|int
     */
    public function updateGroup($groupNo, $data)
    {
        $map = [
            'group_no' => $groupNo
        ];
        return ManageUserGroupModel::getInstance()->save($data, $map);
    }

    /**
     * 更新上级群组
     *
     * @param string $groupNo
     * @param string $groupPno
     * @return false|int
     */
    public function updateGroupPno($groupNo, $groupPno)
    {
        $data = [
            'group_pno' => $groupPno
        ];
        return $this->updateGroup($groupNo, $data);
    }

    /**
     * 更新群组排序
     *
     * @param string $groupNo
     * @param int $groupSort
     * @return false|int
     */
    public function updateGroupSort($groupNo, $groupSort)
    {
        $data = [
            'group_sort' => $groupSort
        ];
        return $this->updateGroup($groupNo, $data);
    }

    /**
     * 删除群组
     *
     * @param string $groupNo
     * @return int
     */
    public function deleteMenu($groupNo)
    {
        $map = [
            'group_no' => $groupNo
        ];
        return ManageUserGroupModel::getInstance()->where($map)->delete();
    }

}