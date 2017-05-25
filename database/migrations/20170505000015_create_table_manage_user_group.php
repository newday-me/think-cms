<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\UserGroupModel;

class CreateTableManageUserGroup extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(UserGroupModel::getInstance()->getTableShortName());
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
        
        // 群组名称
        $groupName = Column::string('group_name', 20)->setDefault('')->setComment('群组名称');
        $table->addColumn($groupName);
        
        // 群组描述
        $groupInfo = Column::string('group_info', 80)->setDefault('')->setComment('群组描述');
        $table->addColumn($groupInfo);
        
        // 管理首页
        $homePage = Column::string('home_page', 150)->setDefault('')->setComment('管理首页');
        $table->addColumn($homePage);
        
        // 群组菜单
        $groupMenus = Column::string('group_menus', 1000)->setDefault('')->setComment('群组菜单');
        $table->addColumn($groupMenus);
        
        // 群组状态
        $groupStatus = Column::integer('group_status')->setLimit(4)
            ->setDefault(0)
            ->setComment('群组状态');
        $table->addColumn($groupStatus);
        
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
