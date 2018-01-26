<?php

use think\migration\Migrator;

class CreateTableManageMenuLink extends Migrator
{

    public function change()
    {
        $this->table('manage_menu_link')
            ->addColumn('group_no', 'char', ['length' => 16, 'comment' => '群组编号'])
            ->addColumn('menu_no', 'char', ['length' => 16, 'comment' => '菜单编号'])
            ->addIndex(['group_no', 'menu_no'], ['unique' => true])
            ->create();
    }

}