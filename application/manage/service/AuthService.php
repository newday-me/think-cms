<?php

namespace app\manage\service;

use core\base\Service;
use app\manage\logic\MenuLogic;
use core\db\manage\data\ManageUserData;

class AuthService extends Service
{

    /**
     * 是否各种操作
     *
     * @param array $actionList
     * @return int
     */
    public function isPublicAction($actionList)
    {
        // 当前操作
        $currentAction = strtolower(MenuLogic::getSingleton()->getCurrentAction());

        // 匹配规则
        $actionPattern = '#(^' . implode(')|(^', $actionList) . ')#i';

        return preg_match($actionPattern, $currentAction);
    }

    /**
     * 是否授权操作
     *
     * @param string $userNo
     * @return bool
     */
    public function isAuthAction($userNo)
    {
        $user = ManageUserData::getSingleton()->getUser($userNo);
        if (empty($user)) {
            return false;
        } elseif (ManageUserData::getSingleton()->isSuperUser($user)) {
            return true;
        }

        $menu = ManageUserData::getSingleton()->getUserMenu($userNo, MenuLogic::getSingleton()->getCurrentAction());
        return $menu ? true : false;
    }

}