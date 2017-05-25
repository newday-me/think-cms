<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\blog\model\ArticleTagModel;

class CreateTableBlogArticleTag extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ArticleTagModel::getInstance()->getTableShortName());
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
        
        // 标签名称
        $tagName = Column::string('tag_name', 20)->setDefault('')->setComment('标签名称');
        $table->addColumn($tagName);
        
        // 标签状态
        $tagStatus = Column::integer('tag_status')->setLimit(4)
            ->setDefault(1)
            ->setComment('标签状态');
        $table->addColumn($tagStatus);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 标签名，唯一索引
        $table->addIndex('tag_name', [
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
