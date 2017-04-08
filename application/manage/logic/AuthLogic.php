<?php
namespace app\manage\logic;

use cms\Auth;
use core\manage\logic\UserLogic;
use core\manage\logic\MenuLogic;

class AuthLogic extends Auth
{

    /**
     * 是否授权的操作
     *
     * @param integer $userId            
     *
     * @return boolean
     */
    public function isAuthAction($userId)
    {
        // 超级管理员
        if (UserLogic::getSingleton()->isSuperAdmin($userId)) {
            return true;
        }
        
        // 操作是否授权
        $menuLogic = MenuLogic::getSingleton();
        $currentMenu = $menuLogic->getMenuByFlag();
        return in_array($currentMenu['id'], $menuLogic->getUserMenuIds($userId));
    }
}