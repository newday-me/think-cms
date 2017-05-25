<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\blog\model\ArticleCateModel;

class CreateTableBlogArticleCate extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ArticleCateModel::getInstance()->getTableShortName());
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
        
        // 分类名称
        $cateName = Column::string('cate_title', 30)->setComment('分类名称');
        $table->addColumn($cateName);
        
        // 分类标识
        $cateFlag = Column::string('cate_name', 20)->setComment('分类标识');
        $table->addColumn($cateFlag);
        
        // 上级分类
        $catePid = Column::integer('cate_pid')->setDefault(0)->setComment('上级分类');
        $table->addColumn($catePid);
        
        // 分类介绍
        $cateInfo = Column::string('cate_info', 150)->setDefault('')->setComment('分类介绍');
        $table->addColumn($cateInfo);
        
        // 分类排序
        $cateSort = Column::integer('cate_sort')->setDefault(0)->setComment('分类排序');
        $table->addColumn($cateSort);
        
        // 分类状态
        $cateStatus = Column::integer('cate_status')->setLimit(4)
            ->setDefault(1)
            ->setComment('分类状态');
        $table->addColumn($cateStatus);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 分类标识，唯一索引
        $table->addIndex('cate_name', [
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
