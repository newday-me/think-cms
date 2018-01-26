<?php

namespace app\manage\logic;

use core\base\Logic;
use core\db\manage\data\ManageUserData;
use core\db\manage\data\ManageUserGroupLinkData;
use core\db\manage\data\ManageUserLoginData;
use core\logic\support\TreeLogic;
use think\facade\Url;

class UserLogic extends Logic
{

    /**
     * 获取用户群组树
     *
     * @param string $userNo
     * @return array
     */
    public function getUserGroupTree($userNo)
    {
        $groupTree = UserGroupLogic::getSingleton()->getGroupTree();

        // 遍历菜单树
        $groupNos = ManageUserGroupLinkData::getSingleton()->getUserGroupNos($userNo);
        TreeLogic::getSingleton()->travelTree($groupTree, function (&$vo) use ($groupNos) {
            $vo['selected'] = in_array($vo['group_no'], $groupNos);
        });

        return $groupTree;
    }

    /**
     * 添加登录日志
     *
     * @param string $userNo
     * @return array|null
     */
    public function addLoginLog($userNo)
    {
        ManageUserData::getSingleton()->updateLogin($userNo);
        return ManageUserLoginData::getSingleton()->addLog($userNo);
    }

    public function getHomeUrl($userNo)
    {
        $menuTree = MenuLogic::getSingleton()->getSideMenu($userNo);
        return $this->getMenuTreeCurrentUrl($menuTree);
    }

    public function getMenuTreeCurrentUrl($menuTree)
    {
        if (empty($menuTree)) {
            return Url::build('manage/index/index');
        } else {
            $current = current($menuTree);
            if (isset($current['children'])) {
                return $this->getMenuTreeCurrentUrl($current['children']);
            } else {
                return $current['menu_link'];
            }
        }
    }

}