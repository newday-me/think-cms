<?php
namespace core\blog\model;

use core\Model;

class ArticleCateModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'blog_article_cate';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 关联文章
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(ArticleModel::class, ArticleCateLinkModel::getInstance()->getTableName(), 'article_id', 'cate_id');
    }

    /**
     * 获取分类列表
     *
     * @return array
     */
    public function getCateList()
    {
        return $this->field('id as value, cate_name as name')
            ->order('cate_sort desc')
            ->select();
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
                'name' => '启用',
                'value' => 1
            ],
            [
                'name' => '禁用',
                'value' => 0
            ]
        ];
    }
}