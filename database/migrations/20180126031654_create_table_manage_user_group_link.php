<?php

use think\migration\Migrator;

class CreateTableManageUserGroupLink extends Migrator
{

    public function change()
    {
        $this->table('manage_user_group_link')
            ->addColumn('user_no', 'char', ['length' => 16, 'comment' => '用户编号'])
            ->addColumn('group_no', 'char', ['length' => 16, 'comment' => '群组编号'])
            ->addIndex(['user_no', 'group_no'], ['unique' => true])
            ->create();
    }

}