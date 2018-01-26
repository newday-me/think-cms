<?php

namespace app\manage\service;

use core\base\Service;
use app\manage\logic\LoginLogic;

class LoginService extends Service
{

    /**
     * 获取登录用户编号
     *
     * @return null|string
     */
    public function getLoginUserNo()
    {
        return LoginLogic::getSingleton()->getLoginUserNo();
    }

}