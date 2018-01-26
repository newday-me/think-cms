<?php

namespace core\db\manage\model;

use core\base\Model;

class ManageMenuModel extends Model
{
    protected $name = 'manage_menu';

    protected $autoWriteTimestamp = true;

    protected $insert = [
        'menu_no'
    ];

    /**
     * 设置群组编号
     *
     * @return string
     */
    public function setMenuNoAttr()
    {
        return $this->newUniqueAttr('menu_no', 16, 'mme_');
    }
}