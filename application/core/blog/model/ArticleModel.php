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