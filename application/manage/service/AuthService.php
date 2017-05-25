<?php
namespace app\manage\service;

use cms\Auth;
use core\manage\logic\UserLogic;
use core\manage\logic\UserGroupLogic;

class AuthService extends Auth
{

    /**
     * 是否授权的操作
     *
     * @return boolean
     */
    public function isAuthAction()
    {
        // 当前用户
        $loginUser = LoginService::getSingleton()->getLoginUser();
        
        // 超级管理员
        if (UserLogic::getSingleton()->isSuperAdmin($loginUser['user_id'])) {
            return true;
        }
        
        // 操作是否授权
        $currentMenu = MenuService::getSingleton()->getCurrentMenu();
        $menuIds = UserGroupLogic::getSingleton()->getGroupMenuIds($loginUser['user_gid']);
        return in_array($currentMenu['id'], $menuIds);
    }
}