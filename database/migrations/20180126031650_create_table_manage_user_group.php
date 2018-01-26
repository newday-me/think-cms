<?php

use think\migration\Migrator;

class CreateTableManageUserGroup extends Migrator
{

    public function change()
    {
        $this->table('manage_user_group')
            ->addColumn('group_no', 'char', ['length' => 16, 'comment' => '群组编号'])
            ->addColumn('group_pno', 'char', ['length' => 16, 'default' => '', 'comment' => '上级群组编号'])
            ->addColumn('group_name', 'string', ['length' => 50, 'comment' => '群组名称'])
            ->addColumn('group_info', 'string', ['length' => 150, 'default' => '', 'comment' => '群组描述'])
            ->addColumn('group_sort', 'integer', ['default' => 0, 'comment' => '群组排序'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间'])
            ->addIndex('group_no', ['unique' => true])
            ->addIndex('group_pno')
            ->create();
    }

}