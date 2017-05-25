<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\blog\model\PageModel;

class CreateTableBlogPage extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(PageModel::getInstance()->getTableShortName());
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
        
        // 页面标题
        $pageTitle = Column::string('page_title', 50)->setComment('标题');
        $table->addColumn($pageTitle);
        
        // 页面名称
        $pageName = Column::string('page_name', 30)->setComment('页面名称');
        $table->addColumn($pageName);
        
        // 页面内容
        $pageContent = Column::mediumText('page_content')->setComment('页面内容');
        $table->addColumn($pageContent);
        
        // 页面排序
        $pageSort = Column::integer('page_sort')->setDefault(0)->setComment('页面排序');
        $table->addColumn($pageSort);
        
        // 页面状态
        $pageStatus = Column::integer('page_status')->setDefault(0)->setComment('页面状态，0：待发布，1：发布，2：菜单项');
        $table->addColumn($pageStatus);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 删除时间
        $deleteTime = Column::integer('delete_time')->setDefault(0)->setComment('删除时间');
        $table->addColumn($deleteTime);
        
        // 页面标识，唯一索引
        $table->addIndex('page_name', [
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
