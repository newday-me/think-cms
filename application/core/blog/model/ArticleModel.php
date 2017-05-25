<?php
namespace core\blog\model;

use core\Model;

class ArticleModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'blog_article';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 新增时自动完成
     *
     * @var array
     */
    protected $insert = [
        'article_key'
    ];

    /**
     * 关联分类
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function cates()
    {
        return $this->belongsToMany(ArticleCateModel::class, ArticleCateLinkModel::getInstance()->getTableShortName(), 'cate_id', 'article_id');
    }

    /**
     * 关联标签
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(ArticleTagModel::class, ArticleTagLinkModel::getInstance()->getTableShortName(), 'tag_id', 'article_id');
    }

    /**
     * 连接分类
     *
     * @return \think\db\Query
     */
    public function withCates()
    {
        $query = $this->aliasQuery();
        return $this->joinCates($query);
    }

    /**
     * 连接标签
     *
     * @return \think\db\Query
     */
    public function withTags()
    {
        $query = $this->aliasQuery();
        return $this->joinTags($query);
    }

    /**
     * 连接分类和标签
     *
     * @return \think\db\Query
     */
    public function withCatesAndTags()
    {
        $query = $this->withCates();
        return $this->joinTags($query);
    }

    /**
     * 别名查询
     *
     * @return \think\db\Query
     */
    protected function aliasQuery()
    {
        return $this->alias('_a_article');
    }

    /**
     * 连接分类
     *
     * @return \think\db\Query
     */
    protected function joinCates($query)
    {
        return $query->join(ArticleCateLinkModel::getInstance()->getTableShortName() . ' _a_cate_link', '_a_article.id = _a_cate_link.article_id')
            ->join(ArticleCateModel::getInstance()->getTableShortName() . ' _a_cate', '_a_cate.id = _a_cate_link.cate_id');
    }

    /**
     * 连接标签
     *
     * @return \think\db\Query
     */
    protected function joinTags($query)
    {
        return $query->join(ArticleTagLinkModel::getInstance()->getTableShortName() . ' _a_tag_link', '_a_article.id = _a_tag_link.article_id')
            ->join(ArticleTagModel::getInstance()->getTableShortName() . ' _a_tag', '_a_tag.id = _a_tag_link.tag_id');
    }

    /**
     * 自动设置文章key
     *
     * @return string
     */
    protected function setArticleKeyAttr()
    {
        return $this->getNewArticleKey();
    }

    /**
     * 获取一个新的文章Key
     *
     * @return string
     */
    public function getNewArticleKey()
    {
        $articleKey = substr(md5(microtime() . rand(1000, 9999)), 8, 16);
        $map = [
            'article_key' => $articleKey
        ];
        $record = $this->where($map)->find();
        if (empty($record)) {
            return $articleKey;
        } else {
            return $this->getNewArticleKey();
        }
    }
}