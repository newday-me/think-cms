<?php

namespace core\db\manage\view;

use think\db\Query;
use core\base\View;
use core\db\manage\model\ManageUserModel;
use core\db\manage\model\ManageUserGroupModel;
use core\db\manage\model\ManageUserGroupLinkModel;
use core\db\manage\model\ManageMenuModel;
use core\db\manage\model\ManageMenuLinkModel;

class ManageUserView extends View
{

    /**
     * 用户菜单
     *
     * @return Query
     */
    public function menuQuery()
    {
        return ManageUserModel::getInstance()->alias('a')
            ->join(ManageUserGroupLinkModel::getInstance()->getName() . ' b', 'b.user_no = a.user_no')
            ->join(ManageUserGroupModel::getInstance()->getName() . ' c', 'c.group_no = b.group_no')
            ->join(ManageMenuLinkModel::getInstance()->getName() . ' d', 'd.group_no = b.group_no')
            ->join(ManageMenuModel::getInstance()->getName() . ' e', 'e.menu_no = d.menu_no');
    }

}