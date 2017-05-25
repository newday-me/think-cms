<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\MenuModel;

class CreateTableManageMenu extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(MenuModel::getInstance()->getTableShortName());
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
        
        // 菜单名称
        $menuName = Column::string('menu_name', 50)->setDefault('')->setComment('菜单名称');
        $table->addColumn($menuName);
        
        // 上级菜单
        $menuPid = Column::integer('menu_pid')->setDefault(0)->setComment('上级菜单');
        $table->addColumn($menuPid);
        
        // 菜单链接
        $menuUrl = Column::string('menu_url', 255)->setDefault('')->setComment('菜单链接');
        $table->addColumn($menuUrl);
        
        // 菜单标识
        $menuFlag = Column::string('menu_flag', 255)->setDefault('')->setComment('菜单标识');
        $table->addColumn($menuFlag);
        
        // 是否Build
        $menuBuild = Column::integer('menu_build')->setLimit(4)
            ->setDefault(1)
            ->setComment('是否Build');
        $table->addColumn($menuBuild);
        
        // 打开方式
        $menuTarget = Column::string('menu_target', 10)->setDefault('_self')->setComment('打开方式');
        $table->addColumn($menuTarget);
        
        // 菜单排序
        $menuSort = Column::integer('menu_sort')->setDefault(0)->setComment('菜单排序');
        $table->addColumn($menuSort);
        
        // 菜单状态
        $menuStatus = Column::integer('menu_status')->setLimit(4)
            ->setDefault(1)
            ->setComment('菜单状态');
        $table->addColumn($menuStatus);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 上级菜单，普通索引
        $table->addIndex('menu_pid');
        
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
