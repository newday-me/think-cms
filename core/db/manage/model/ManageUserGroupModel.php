<?php

namespace core\db\manage\model;

use core\base\Model;

class ManageUserGroupModel extends Model
{
    protected $pk = 'group_no';

    protected $name = 'manage_user_group';

    protected $autoWriteTimestamp = true;

    protected $insert = [
        'group_no'
    ];

    /**
     * 设置群组编号
     *
     * @return string
     */
    public function setGroupNoAttr()
    {
        return $this->newUniqueAttr('group_no', 16, 'mug_');
    }
}