<?php

use think\migration\Migrator;

class CreateTableManageUserLogin extends Migrator
{
    public function change()
    {
        $this->table('manage_user_login')
            ->addColumn('user_no', 'char', ['length' => 16, 'comment' => '用户编号'])
            ->addColumn('login_ip', 'string', ['length' => 20, 'default' => '', 'comment' => '登录IP'])
            ->addColumn('login_agent', 'string', ['length' => 250, 'default' => '', 'comment' => '浏览器信息'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间'])
            ->addIndex('user_no')
            ->create();
    }
}