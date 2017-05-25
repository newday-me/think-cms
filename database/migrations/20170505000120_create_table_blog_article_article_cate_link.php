<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\blog\model\ArticleCateLinkModel;

class CreateTableBlogArticleArticleCateLink extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ArticleCateLinkModel::getInstance()->getTableShortName());
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
        
        // 文章ID
        $articleId = Column::integer('article_id')->setComment('文章ID');
        $table->addColumn($articleId);
        
        // 分类ID
        $cateId = Column::integer('cate_id')->setComment('分类ID');
        $table->addColumn($cateId);
        
        // 链接ID，唯一索引
        $table->addIndex([
            'article_id',
            'cate_id'
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
