<?php
namespace core\blog\model;

use core\Model;

class ArticleTagModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'blog_article_tag';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

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
                'name' => '停用',
                'value' => 0
            ]
        ];
    }
}