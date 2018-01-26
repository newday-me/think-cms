<?php

namespace app\manage\logic;

use core\base\Logic;
use think\facade\Cookie;
use think\facade\Cache;

class LoginLogic extends Logic
{
    /**
     * 用户令牌Cookie名称
     */
    const COOKIE_NAME_USER_TOKEN = 'user_token';

    /**
     * 获取登录
     */
    public function getLogin()
    {
        $userToken = Cookie::get(self::COOKIE_NAME_USER_TOKEN);
        if (empty($userToken)) {
            return null;
        }
        return Cache::get($userToken);
    }

    /**
     * 获取登录的用户编号
     *
     * @return string|null
     */
    public function getLoginUserNo()
    {
        $data = $this->getLogin();
        if (is_array($data) && isset($data['user_no'])) {
            return $data['user_no'];
        } else {
            return null;
        }
    }

    /**
     * 设置登录
     *
     * @param string $userNo
     * @param mixed $data
     * @param int $expire
     */
    public function setLogin($userNo, $data, $expire = 0)
    {
        $userToken = $this->generateUserToken($userNo);
        Cookie::set(self::COOKIE_NAME_USER_TOKEN, $userToken, $expire);
        Cache::set($userToken, $data, $expire);
    }

    /**
     * 清除登录
     */
    public function clearLogin()
    {
        Cookie::delete(self::COOKIE_NAME_USER_TOKEN);
    }

    /**
     * 生成用户令牌
     *
     * @param string $userNo
     * @return string
     */
    protected function generateUserToken($userNo)
    {
        return $userNo . '|' . time();
    }
}