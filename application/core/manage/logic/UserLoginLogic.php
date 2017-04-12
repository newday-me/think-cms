<?php
namespace core\manage\logic;

use cms\Common;
use core\Logic;
use core\manage\model\UserLoginModel;

class UserLoginLogic extends Logic
{

    /**
     * 增加登录日志
     *
     * @param integer $userId            
     * @return boolean
     */
    public function addLogin($userId)
    {
        $model = UserLoginModel::getInstance();
        $common = Common::getSingleton();
        $data = [
            'login_uid' => $userId,
            'login_ip' => $common->getIp(),
            'login_agent' => $common->getAgent()
        ];
        return $model->save($data);
    }
}