<?php

namespace core\db\manage\model;

use core\base\Model;

class ManageUserLoginModel extends Model
{
    protected $name = 'manage_user_login';

    protected $autoWriteTimestamp = true;

    /**
     * 关联用户
     *
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(ManageUserModel::class, 'user_no', 'user_no');
    }
}