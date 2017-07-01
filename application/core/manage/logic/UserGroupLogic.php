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
        $nest = $this->getGroupNest();
        $tree = $nest['tree'];
        
        $list = [];
        $list[] = [
            'name' => '无',
            'value' => 0
        ];
        if (isset($tree[0])) {
            foreach ($tree[0] as $vo) {
                $list[] = [
                    'name' => $vo['group_name'],
                    'value' => $vo['id']
                ];
            }
        }
        
        return $list;
    }

    /**
     * 获取列表下拉-用户
     *
     * @return array
     */
    public function getSelectListForUser()
    {
        $nest = $this->getGroupNest();
        $tree = $nest['tree'];
        
        $list = [];
        if (isset($tree[0])) {
            foreach ($tree[0] as $vo) {
                $list[] = [
                    'name' => $vo['group_name'],
                    'value' => $vo['id']
                ];
                if (isset($tree[$vo['id']])) {
                    foreach ($tree[$vo['id']] as $ko) {
                        $list[] = [
                            'name' => '--' . $ko['group_name'],
                            'value' => $ko['id']
                        ];
                    }
                }
            }
        }
        
        return $list;
    }

    /**
     * 获取群组Nest
     *
     * @return array
     */
    public function getGroupNest()
    {
        $list = UserGroupModel::getInstance()->cache(2)
            ->order('group_sort desc')
            ->column('*', 'id');
        
        $tree = [];
        foreach ($list as $vo) {
            $menuPid = $vo['group_pid'];
            if (! isset($tree[$menuPid])) {
                $tree[$menuPid] = [];
            }
            $tree[$menuPid][] = $vo;
        }
        
        return [
            'list' => $list,
            'tree' => $tree
        ];
    }

    /**
     * 保存权限
     *
     * @param integer $gid            
     * @param array $groupMenus            
     * @return boolean
     */
    public function saveAuth($gid, $groupMenus)
    {
        $group = UserGroupModel::get($gid);
        if (empty($group)) {
            return false;
        } elseif ($group['group_pid'] == 0) {
            
            // 更新子群组菜单
            $map = [
                'group_pid' => $gid
            ];
            $subGroups = UserGroupModel::getInstance()->where($map)->select();
            foreach ($subGroups as $subGroup) {
                $map = [
                    'id' => $subGroup['id']
                ];
                $subGroupMenus = array_intersect($groupMenus, explode(',', $subGroup['group_menus']));
                $data = [
                    'group_menus' => implode(',', $subGroupMenus)
                ];
                UserGroupModel::getInstance()->update($data, $map);
            }
            
            // 更新群组菜单
            $map = [
                'id' => $gid
            ];
            $data = [
                'group_menus' => implode(',', $groupMenus)
            ];
            return UserGroupModel::getInstance()->update($data, $map) ? true : false;
        } else {
            // 获取父群组菜单
            $parentGroupMenus = $this->getGroupMenuIdsParent($gid);
            
            // 更新群组菜单
            $groupMenus = array_intersect($parentGroupMenus, $groupMenus);
            $map = [
                'id' => $gid
            ];
            $data = [
                'group_menus' => implode(',', $groupMenus)
            ];
            return UserGroupModel::getInstance()->update($data, $map) ? true : false;
        }
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

    /**
     * 群组菜单
     *
     * @param number $groupId            
     * @return array
     */
    public function getGroupMenuIdsParent($groupId)
    {
        $group = UserGroupModel::get($groupId);
        if ($group && $group['group_pid'] > 0) {
            return $this->getGroupMenuIds($group['group_pid']);
        }
        return [];
    }
}