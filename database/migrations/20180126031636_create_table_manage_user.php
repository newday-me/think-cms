<?php

use think\migration\Migrator;

class CreateTableManageUser extends Migrator
{
    public function change()
    {
        $this->table('manage_user')
            ->addColumn('user_no', 'char', ['length' => 16, 'comment' => '用户编号'])
            ->addColumn('user_name', 'string', ['length' => 16, 'comment' => '用户名'])
            ->addColumn('user_password', 'char', ['length' => 32, 'comment' => '密码'])
            ->addColumn('user_nick', 'string', ['length' => 150, 'default' => '', 'comment' => '昵称'])
            ->addColumn('user_status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '状态，0：禁用，1：启用'])
            ->addColumn('login_time', 'integer', ['default' => 0, 'comment' => '登录时间'])
            ->addColumn('login_ip', 'string', ['length' => 20, 'default' => '', 'comment' => '登录IP'])
            ->addColumn('login_count', 'integer', ['default' => 0, 'comment' => '登录次数'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间'])
            ->addIndex('user_no', ['unique' => true])
            ->addIndex('user_name', ['unique' => true])
            ->create();
    }
    
}