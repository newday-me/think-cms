<?php

namespace app\manage\service;

use app\manage\logic\LoginLogic;
use app\manage\logic\UserLogic;
use core\base\Service;
use core\db\manage\constant\ManageUserConstant;
use core\db\manage\data\ManageUserData;

class StartService extends Service
{

    /**
     * 登录
     *
     * @param string $userName
     * @param string $userPassword
     * @return \cms\core\objects\ReturnObject
     */
    public function login($userName, $userPassword)
    {
        $manageUser = ManageUserData::getSingleton()->checkLogin($userName, $userPassword);
        if (empty($manageUser)) {
            return $this->returnError('账号不存在');
        } elseif ($manageUser['user_status'] == ManageUserConstant::STATUS_DISABLE) {
            return $this->returnError('账号被禁用');
        }

        // 设置登录
        $userNo = $manageUser['user_no'];
        $data = [
            'user_no' => $userNo
        ];
        $expire = 7 * 86400;
        LoginLogic::getSingleton()->setLogin($userNo, $data, $expire);

        // 登录日志
        UserLogic::getSingleton()->addLoginLog($userNo);

        // 跳转链接
        $homeUrl = UserLogic::getSingleton()->getHomeUrl($userNo);

        return $this->returnSuccess('登录成功', [
            'user_no' => $userNo,
            'home_url' => $homeUrl
        ]);
    }

    /**
     * 退出
     */
    public function logout()
    {
        LoginLogic::getSingleton()->clearLogin();
    }

}