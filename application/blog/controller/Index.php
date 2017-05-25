<?php
namespace app\blog\controller;

use think\Request;
use cms\Controller;
use core\blog\logic\ArticleCateLogic;
use core\blog\logic\ArticleLogic;
use core\blog\model\PageModel;
use core\blog\model\ArticleCateModel;
use app\blog\service\ViewService;
use app\blog\service\IndexService;

class Index extends Controller
{

    /**
     * 网站标题
     *
     * @var unknown
     */
    protected $siteTitle;

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::_initialize()
     */
    public function _initialize()
    {
        parent::_initialize();
        
        $this->assignArticleCate();
        
        $this->assignBlogPage();
        
        $this->assignArticleListSidebar();
        
        $this->assignArticleTags();
    }

    /**
     * 首页
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '首页';
        
        $articleList = IndexService::getSingleton()->getArticleListHome();
        $this->assign('article_list', $articleList);
        
        return $this->fetch('index');
    }

    /**
     * 分类
     *
     * @param Request $request            
     * @return string
     */
    public function cate(Request $request)
    {
        $cateName = $request->param('name');
        if (empty($cateName)) {
            $this->error('页面不存在');
        }
        
        $map = [
            'cate_name' => $cateName,
            'cate_status' => 1
        ];
        $cate = ArticleCateModel::getInstance()->where($map)->find();
        if (empty($cate)) {
            $this->error('分类不存在');
        }
        $this->siteTitle = $cate['cate_title'];
        
        $articleList = IndexService::getSingleton()->getArticleListCate($cateName);
        $this->assign('article_list', $articleList);
        
        return $this->fetch('index');
    }

    /**
     * 标签
     *
     * @param Request $request            
     * @return string
     */
    public function tag(Request $request)
    {
        $tagName = $request->param('name');
        if (empty($tagName)) {
            $this->error('页面不存在');
        }
        $this->siteTitle = $tagName;
        
        $articleList = IndexService::getSingleton()->getArticleListTag($tagName);
        $this->assign('article_list', $articleList);
        
        return $this->fetch('index');
    }

    /**
     * 单页面
     *
     * @param Request $request            
     * @return string
     */
    public function page(Request $request)
    {
        $pageName = $request->param('name');
        if (empty($pageName)) {
            $this->error('页面不存在');
        }
        
        $map = [
            'delete_time' => 0,
            'page_status' => [
                'gt',
                0
            ]
        ];
        $page = PageModel::getInstance()->where($map)->find();
        if (empty($page)) {
            $this->error('页面不存在');
        }
        $this->assign('page', $page);
        
        $this->siteTitle = $page['page_title'];
        
        return $this->fetch();
    }

    /**
     * 文章详情
     *
     * @param Request $request            
     * @return string
     */
    public function show(Request $request)
    {
        $articleKey = $request->param('key');
        if (empty($articleKey)) {
            $this->error('页面不存在');
        }
        
        // 查找文章
        $article = IndexService::getSingleton()->getArticle($articleKey);
        if (empty($article)) {
            $this->error('文章不存在');
        }
        $this->assign('article', $article);
        
        // 增加访问
        ArticleLogic::getSingleton()->addVisit($article['id']);
        
        // 网站标题
        $this->siteTitle = $article['article_title'];
        
        return $this->fetch();
    }

    /**
     * 赋值文章分类
     *
     * @return void
     */
    protected function assignArticleCate()
    {
        $nest = ArticleCateLogic::getSingleton()->getCateNest();
        $this->assign('cate_tree', $nest['tree']);
    }

    /**
     * 赋值博客单页面
     *
     * @return void
     */
    protected function assignBlogPage()
    {
        $pageList = IndexService::getSingleton()->getBlogPageList();
        $this->assign('page_list', $pageList);
    }

    /**
     * 赋值最新、热门、随机文章列表
     *
     * @return void
     */
    protected function assignArticleListSidebar()
    {
        $indexService = IndexService::getSingleton();
        
        // 最新文章
        $listNew = $indexService->getArticleListAside('create_time desc', 10);
        $this->assign('article_list_new', $listNew);
        
        // 热门文章
        $listHot = $indexService->getArticleListAside('article_visit desc, create_time desc', 10);
        $this->assign('article_list_hot', $listHot);
        
        // 随机文章
        $listRand = $indexService->getArticleListAside('rand()', 10);
        $this->assign('article_list_rand', $listRand);
    }

    /**
     * 赋值文章标签
     *
     * @return void
     */
    protected function assignArticleTags()
    {
        $tagList = IndexService::getSingleton()->getArticleTagList(20);
        $this->assign('tag_list', $tagList);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::beforeViewRender()
     */
    protected function beforeViewRender()
    {
        // 网站标题
        $this->assign('site_title', $this->siteTitle);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::getView()
     */
    protected function getView()
    {
        return ViewService::getSingleton()->getView();
    }
}