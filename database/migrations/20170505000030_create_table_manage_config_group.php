<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\ConfigGroupModel;

class CreateTableManageConfigGroup extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ConfigGroupModel::getInstance()->getTableShortName());
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
        
        // 分组名称
        $groupName = Column::string('group_name', 30)->setComment('分组名称');
        $table->addColumn($groupName);
        
        // 分组描述
        $groupInfo = Column::string('group_info', 250)->setDefault('')->setComment('分组描述');
        $table->addColumn($groupInfo);
        
        // 分组排序
        $groupSort = Column::integer('group_sort')->setDefault(0)->setComment('分组排序');
        $table->addColumn($groupSort);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
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
