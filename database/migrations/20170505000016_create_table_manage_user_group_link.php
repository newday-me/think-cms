<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\UserGroupLinkModel;

class CreateTableManageUserGroupLink extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(UserGroupLinkModel::getInstance()->getTableShortName());
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::up()
     */
    public function up()
    {
        $table = $this->getTable();
        
        // 用户ID
        $userId = Column::integer('user_id')->setDefault(0)->setComment('用户ID');
        $table->addColumn($userId);
        
        // 群组ID
        $groupId = Column::integer('group_id')->setDefault(0)->setComment('群组ID');
        $table->addColumn($groupId);
        
        // 链接ID，唯一索引
        $table->addIndex([
            'user_id',
            'group_id'
        ], [
            'unique' => true
        ]);
        
        // 保存
        $table->save();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $this->getTable()->drop();
    }
}
