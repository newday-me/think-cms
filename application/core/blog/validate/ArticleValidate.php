<?php
namespace core\blog\validate;

use core\Validate;

class ArticleValidate extends Validate
{

    /**
     * 规则
     *
     * @var unknown
     */
    protected $rule = [
        'article_type' => 'require',
        'article_title' => 'require',
        'article_author' => 'require',
        'article_info' => 'require',
        'article_cover' => 'require',
        'article_content' => 'require'
    ];

    /**
     * 提示
     *
     * @var unknown
     */
    protected $message = [
        'article_type.require' => '文章类型为空',
        'article_title.require' => '文章标题为空',
        'article_author.require' => '文章作者为空',
        'article_info.require' => '文章简介为空',
        'article_cover.require' => '文章封面为空',
        'article_content.require' => '文章内容为空'
    ];

    /**
     * 场景
     *
     * @var unknown
     */
    protected $scene = [
        'add' => [
            'article_type',
            'article_title',
            'article_author',
            'article_info',
            'article_cover',
            'article_content'
        ],
        'edit' => [
            'article_title',
            'article_author',
            'article_info',
            'article_cover',
            'article_content'
        ]
    ];
}