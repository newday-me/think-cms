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
}