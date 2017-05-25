<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\blog\model\ArticleModel;

class CreateTableBlogArticle extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ArticleModel::getInstance()->getTableShortName());
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
        
        // 文章标识
        $articleKey = Column::string('article_key', 16)->setComment('文章标识');
        $table->addColumn($articleKey);
        
        // 文章标题
        $articleTitle = Column::string('article_title', 150)->setDefault('')->setComment('文章标题');
        $table->addColumn($articleTitle);
        
        // 文章作者
        $articleAuthor = Column::string('article_author', 30)->setDefault('')->setComment('文章作者');
        $table->addColumn($articleAuthor);
        
        // 文章简介
        $articleInfo = Column::string('article_info', 250)->setDefault('')->setComment('文章简介');
        $table->addColumn($articleInfo);
        
        // 文章封面
        $articleCover = Column::string('article_cover', 250)->setDefault('')->setComment('文章封面');
        $table->addColumn($articleCover);
        
        // 原文链接
        $articleOrigin = Column::string('article_origin', 250)->setDefault('')->setComment('原文链接');
        $table->addColumn($articleOrigin);
        
        // 文章排序
        $articleSort = Column::integer('article_sort')->setDefault(0)->setComment('文章排序');
        $table->addColumn($articleSort);
        
        // 文章内容
        $articleContent = Column::mediumText('article_content')->setComment('文章内容');
        $table->addColumn($articleContent);
        
        // 浏览次数
        $articleVisit = Column::integer('article_visit')->setDefault(0)->setComment('浏览次数');
        $table->addColumn($articleVisit);
        
        // 文章状态
        $articleStatus = Column::integer('article_status')->setLimit(4)
            ->setDefault(0)
            ->setComment('文章状态');
        $table->addColumn($articleStatus);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 删除时间
        $deleteTime = Column::integer('delete_time')->setDefault(0)->setComment('删除时间');
        $table->addColumn($deleteTime);
        
        // 文章标识，唯一索引
        $table->addIndex('article_key', [
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
