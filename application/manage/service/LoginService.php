<?php
namespace app\manage\service;

use think\Url;
use cms\Service;
use core\manage\logic\UserLogic;
use core\manage\model\UserModel;
use app\common\App;

class LoginService extends Service
{

    /**
     * 登录存储Key
     *
     * @var unknown
     */
    const LOGIN_KEY = 'manage_user';

    /**
     * 登录操作
     *
     * @param string $name            
     * @param string $passwd            
     * @return array($success, $msg, $url)
     */
    public function doLogin($name, $passwd)
    {
        list ($code, $msg, $user, $group) = UserLogic::getSingleton()->checkLogin($name, $passwd);
        if ($code == 1) {
            
            // 保存登录
            $loginDriver = $this->getLoginDriver();
            $data = [
                'user_id' => $user['id'],
                'user_gid' => $group['id'],
                'manage_url' => Url::build($group['home_page'])
            ];
            $loginDriver->storageLogin(self::LOGIN_KEY, $data);
            
            return [
                true,
                '登录成功',
                $data['manage_url']
            ];
        } else {
            return [
                false,
                $msg,
                null
            ];
        }
    }

    /**
     * 注销登录
     */
    public function loginOut()
    {
        $loginDriver = $this->getLoginDriver();
        return $loginDriver->clearLogin(self::LOGIN_KEY);
    }

    /**
     * 登录用户
     *
     * @return array
     */
    public function getLoginUser()
    {
        $loginDriver = $this->getLoginDriver();
        return $loginDriver->readLogin(self::LOGIN_KEY);
    }

    /**
     * 登录用户信息
     *
     * @return array
     */
    public function gteLoginUserInfo()
    {
        $user = $this->getLoginUser();
        if (empty($user)) {
            return null;
        }
        
        return UserModel::getInstance()->get($user['user_id']);
    }

    /**
     * 获取登录驱动
     *
     * @return \cms\Login
     */
    public function getLoginDriver()
    {
        return App::getSingleton()->login;
    }
}