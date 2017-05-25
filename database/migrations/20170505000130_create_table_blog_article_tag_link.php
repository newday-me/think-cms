<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\blog\model\ArticleTagLinkModel;

class CreateTableBlogArticleTagLink extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ArticleTagLinkModel::getInstance()->getTableShortName());
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
        
        // 标签ID
        $tagId = Column::integer('tag_id')->setComment('标签ID');
        $table->addColumn($tagId);
        
        // 链接ID，唯一索引
        $table->addIndex([
            'article_id',
            'tag_id'
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
