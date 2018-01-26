<?php

namespace core\db\manage\model;

use core\base\Model;

class ManageUserModel extends Model
{
    protected $pk = 'user_no';

    protected $name = 'manage_user';

    protected $autoWriteTimestamp = true;

    protected $insert = [
        'user_no'
    ];

    /**
     * 设置用户编号
     *
     * @return string
     */
    public function setUserNoAttr()
    {
        return $this->newUniqueAttr('user_no', 16, 'mus_');
    }

    /**
     * 关联群组
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(ManageUserGroupModel::class, ManageUserGroupLinkModel::getInstance()->getName(), 'group_no', 'user_no');
    }
}