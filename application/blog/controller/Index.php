<?php
namespace app\blog\controller;

use think\Request;
use cms\Controller;
use core\blog\model\ArticleModel;
use core\blog\model\ArticleCateModel;
use app\manage\service\ViewService;

class Index extends Controller
{

    /**
     * 网站标题
     *
     * @var unknown
     */
    protected $siteTitle;

    /**
     * 当前菜单样式
     *
     * @var unknown
     */
    protected $menuClass;

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::_initialize()
     */
    public function _initialize()
    {
        parent::_initialize();
        
        // 默认样式
        $request = Request::instance();
        $this->menuClass = strtolower($request->controller() . '_' . $request->action());
        
        $this->assignCateList();
    }

    /**
     * 首页
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '博客 - 首页';
        
        $map = [
            'article_status' => 1,
            'cate_status' => 1
        ];
        return $this->displayList($map);
    }

    /**
     * 分类
     *
     * @param Request $request            
     * @return string
     */
    public function cate(Request $request)
    {
        // 分类
        $cateName = $request->param('name');
        if (empty($cateName)) {
            $this->error('链接错误');
        }
        
        // 检查分类
        $map = [
            'cate_flag' => $cateName
        ];
        $cate = ArticleCateModel::getInstance()->where($map)->find();
        if (empty($cate)) {
            $this->error('分类不存在');
        } elseif ($cate['cate_status'] == 0) {
            $this->error('分类未启用');
        }
        
        $this->siteTitle = '博客 - ' . $cate['cate_name'];
        $this->menuClass = 'cate_' . $cateName;
        $map = [
            'article_status' => 1,
            'cate_id' => $cate['id']
        ];
        return $this->displayList($map);
    }

    /**
     * 详情
     *
     * @param Request $request            
     * @return string
     */
    public function show(Request $request)
    {
        $articleKey = $request->param('key');
        if (empty($articleKey)) {
            $this->error('链接错误');
        }
        
        $map = [
            'article_key' => $articleKey
        ];
        $article = ArticleModel::getInstance()->where($map)->find();
        if (empty($article)) {
            $this->error('文章不存在');
        } elseif ($article['article_status'] == 0) {
            $this->error('文章审核中');
        }
        $this->assign('article', $article);
        
        $this->siteTitle = '博客 - ' . $article['article_title'];
        $this->menuClass = 'index_index';
        
        return $this->fetch();
    }

    /**
     * 显示列表
     *
     * @param array $map            
     * @return string
     */
    protected function displayList($map)
    {
        
        // 幻灯片列表
        $this->assignSliderList($map);
        
        // 分页列表
        $this->assignPageList($map);
        
        return $this->fetch('index');
    }

    /**
     * 赋值分页列表
     *
     * @return void
     */
    protected function assignPageList($map)
    {
        $model = ArticleModel::getInstance();
        $query = $model->withCates($model);
        $list = $query->where($map)
            ->field('article_key, article_title, article_author, article_info, article_cover, _a_article.create_time')
            ->group('_a_article.id')
            ->order('_a_article.id desc')
            ->paginate(5);
        $this->assign('_list', $list);
        $this->assign('_page', $list->render());
        $this->assign('_total', $list->total());
    }

    /**
     * 赋值幻灯片
     *
     * @return void
     */
    protected function assignSliderList($map)
    {
        $model = ArticleModel::getInstance();
        $query = $model->withCates($model);
        $sliderList = $query->where($map)
            ->field('article_key, article_title, article_author, article_info, article_cover')
            ->group('_a_article.id')
            ->order('rand()')
            ->select();
        $this->assign('slider_list', $sliderList);
    }

    /**
     * 赋值分类列表
     *
     * @return void
     */
    protected function assignCateList()
    {
        $map = [
            'cate_status' => 1
        ];
        $cateList = ArticleCateModel::getInstance()->where($map)
            ->order('cate_sort desc')
            ->select();
        foreach ($cateList as &$vo) {
            $vo['cate_class'] = 'cate_' . $vo['cate_flag'];
        }
        unset($vo);
        $this->assign('cate_list', $cateList);
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
        
        // 菜单样式
        $this->assign('menu_class', $this->menuClass);
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