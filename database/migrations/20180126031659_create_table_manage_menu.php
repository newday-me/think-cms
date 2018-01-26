<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableManageMenu extends Migrator
{

    public function change()
    {
        $this->table('manage_menu')
            ->addColumn('menu_no', 'char', ['length' => 16, 'comment' => '菜单编号'])
            ->addColumn('menu_pno', 'char', ['length' => 16, 'default' => '', 'comment' => '上级菜单编号'])
            ->addColumn('menu_name', 'string', ['length' => 50, 'comment' => '菜单名称'])
            ->addColumn('menu_icon', 'string', ['length' => 30, 'default' => '', 'comment' => '菜单图标'])
            ->addColumn('menu_url', 'string', ['length' => 250, 'default' => '', 'comment' => '菜单链接'])
            ->addColumn('menu_action', 'string', ['length' => 150, 'default' => '', 'comment' => '菜单操作'])
            ->addColumn('menu_build', 'integer', ['length' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '是否构建'])
            ->addColumn('menu_target', 'string', ['length' => 20, 'default' => '_self', 'comment' => '打开方式'])
            ->addColumn('menu_type', 'integer', ['length' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '菜单类型，1：菜单 2：操作'])
            ->addColumn('menu_status', 'integer', ['length' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '菜单状态，0：禁用，1：启用'])
            ->addColumn('menu_sort', 'integer', ['default' => 0, 'comment' => '菜单排序'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间'])
            ->addIndex('menu_no', ['unique' => true])
            ->addIndex('menu_action')
            ->create();
    }

}