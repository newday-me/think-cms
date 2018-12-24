<?php

namespace app\manage\service;

use core\base\Service;
use app\manage\logic\LoginLogic;
use app\manage\logic\UserLogic;
use core\db\manage\constant\ManageUserConstant;
use core\db\manage\data\ManageUserData;

class StartService extends Service
{

    /**
     * 登录
     *
     * @param string $userName
     * @param string $userPassword
     * @return array|null
     */
    public function login($userName, $userPassword)
    {
        $this->resetError();

        $manageUser = ManageUserData::getSingleton()->checkLogin($userName, $userPassword);
        if (empty($manageUser)) {
            $this->setError(self::ERROR_CODE_DEFAULT, '账号不存在');
            return null;
        } elseif ($manageUser['user_status'] == ManageUserConstant::STATUS_DISABLE) {
            $this->setError(self::ERROR_CODE_DEFAULT, '账号被禁用');
            return null;
        }

        // 设置登录
        $userNo = $manageUser['user_no'];
        $data = [
            'user_no' => $userNo,
            'user_nick' => $manageUser['user_nick']
        ];
        $expire = 7 * 86400;
        LoginLogic::getSingleton()->setLogin($userNo, $data, $expire);

        // 登录日志
        UserLogic::getSingleton()->addLoginLog($userNo);

        // 跳转链接
        $homeUrl = UserLogic::getSingleton()->getHomeUrl($userNo);

        return [
            'user_no' => $userNo,
            'home_url' => $homeUrl
        ];
    }

    /**
     * 退出
     */
    public function logout()
    {
        LoginLogic::getSingleton()->clearLogin();
    }

}