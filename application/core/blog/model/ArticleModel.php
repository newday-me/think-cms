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
        return $this->belongsToMany(ArticleCateModel::class, ArticleCateLinkModel::getInstance()->getTableName(), 'cate_id', 'article_id');
    }

    /**
     * 关联标签
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(ArticleTagModel::class, ArticleTagLinkModel::getInstance()->getTableName(), 'tag_id', 'article_id');
    }

    /**
     * 使用别名
     *
     * @param unknown $query            
     */
    public function useAlias($query)
    {
        return $query->alias('_a_article');
    }

    /**
     * 连接分类
     *
     * @return \think\db\Query
     */
    public function withCates($query)
    {
        $query = $this->useAlias($query);
        return $query->join(ArticleCateLinkModel::getInstance()->getTableShortName() . ' _a_cate_link', '_a_article.id = _a_cate_link.article_id')
            ->join(ArticleCateModel::getInstance()->getTableShortName() . ' _a_cate', '_a_cate.id = _a_cate_link.cate_id');
    }

    /**
     * 连接标签
     *
     * @return \think\db\Query
     */
    public function withTags($query)
    {
        $query = $this->useAlias($query);
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

    /**
     * 获取状态列表
     *
     * @return array
     */
    public function getStatusList()
    {
        return [
            [
                'name' => '发布',
                'value' => 1
            ],
            [
                'name' => '待发布',
                'value' => 0
            ]
        ];
    }
}