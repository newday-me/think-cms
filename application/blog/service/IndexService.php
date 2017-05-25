<?php
namespace app\blog\service;

use think\Url;
use cms\Service;
use core\blog\logic\ArticleLogic;
use core\blog\model\PageModel;
use core\blog\model\ArticleModel;
use core\blog\model\ArticleTagModel;

class IndexService extends Service
{

    /**
     * 获取文章
     *
     * @param string $articleKey            
     * @return array
     */
    public function getArticle($articleKey)
    {
        $map = [
            'article_key' => $articleKey,
            'article_status' => 1
        ];
        $article = ArticleModel::getInstance()->where($map)->find();
        if ($article) {
            
            // 增加访问
            ArticleLogic::getSingleton()->addVisit($article['id']);
            
            // 处理文章
            $article = $this->processArticleItem($article);
        }
        return $article;
    }

    /**
     * 获取首页文章列表
     *
     * @return array
     */
    public function getArticleListHome()
    {
        $map = [
            'article_status' => 1,
            'delete_time' => 0
        ];
        $field = 'id, article_key, article_title, article_info, article_cover, article_visit, create_time';
        $list = ArticleModel::getInstance()->with('cates')
            ->where($map)
            ->field($field)
            ->order('create_time desc')
            ->cache(5)
            ->paginate(5);
        return $this->processArticleList($list);
    }

    /**
     * 获取分类文章列表
     *
     * @param string $cateName            
     * @return array
     */
    public function getArticleListCate($cateName)
    {
        $map = [
            'article_status' => 1,
            'delete_time' => 0,
            'cate_name' => $cateName
        ];
        $field = '_a_article.id, article_key, article_title, article_info, article_cover, article_visit, _a_article.create_time';
        $list = ArticleModel::getInstance()->withCates()
            ->with('cates')
            ->where($map)
            ->field($field)
            ->order('create_time desc')
            ->cache(5)
            ->paginate(5);
        return $this->processArticleList($list);
    }

    /**
     * 获取标签文章列表
     *
     * @param string $cateName            
     * @return array
     */
    public function getArticleListTag($tagName)
    {
        $map = [
            'article_status' => 1,
            'delete_time' => 0,
            'tag_name' => $tagName
        ];
        $field = '_a_article.id, article_key, article_title, article_info, article_cover, article_visit, _a_article.create_time';
        $list = ArticleModel::getInstance()->withTags()
            ->with('cates')
            ->where($map)
            ->field($field)
            ->order('create_time desc')
            ->cache(5)
            ->paginate(5);
        return $this->processArticleList($list);
    }

    /**
     * 获取页面列表
     *
     * @return array
     */
    public function getBlogPageList()
    {
        $map = [
            'page_status' => 2,
            'delete_time' => 0
        ];
        $field = 'page_name, page_title';
        return PageModel::getInstance()->where($map)
            ->field($field)
            ->cache(5)
            ->select();
    }

    /**
     * 获取文章标签列表
     *
     * @param integer $num            
     * @return array
     */
    public function getArticleTagList($num = 20)
    {
        $map = [
            'tag_status' => 1
        ];
        return ArticleTagModel::getInstance()->where($map)
            ->limit($num)
            ->order('rand()')
            ->cache(30)
            ->select();
    }

    /**
     * 获取侧边文章列表
     *
     * @param string $order            
     * @param integer $num            
     * @return array
     */
    public function getArticleListAside($order, $num = 10)
    {
        $map = [
            'delete_time' => 0,
            'article_status' => 1
        ];
        $field = 'id, article_key, article_title';
        return ArticleModel::getInstance()->where($map)
            ->field($field)
            ->order($order)
            ->limit($num)
            ->cache(30)
            ->select();
    }

    /**
     * 处理文章列表
     *
     * @param array $list            
     * @return array
     */
    public function processArticleList($list)
    {
        foreach ($list as &$vo) {
            $vo = $this->processArticleItem($vo);
        }
        unset($vo);
        return $list;
    }

    /**
     * 处理单篇文章
     *
     * @param array $article            
     * @return array
     */
    public function processArticleItem($article)
    {
        // 时间
        $article['create_time_str'] = date('Y-m-d', strtotime($article['create_time']));
        
        // 链接
        $article['article_link'] = Url::build('blog/index/show', [
            'key' => $article['article_key']
        ]);
        
        return $article;
    }
}