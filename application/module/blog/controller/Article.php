<?php
namespace module\blog\controller;

use think\Request;
use core\blog\logic\ArticleLogic;
use core\blog\model\ArticleModel;
use core\blog\model\ArticleCateModel;
use core\blog\validate\ArticleValidate;

class Article extends Base
{

    /**
     * 文章列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '文章列表';
        
        // 文章列表
        $map = [
            'delete_time' => 0
        ];
        $this->assignArticleList($map);
        
        return $this->fetch();
    }

    /**
     * 回收站
     *
     * @param Request $request            
     *
     * @return string
     */
    public function recycle(Request $request)
    {
        $this->siteTitle = '文章回收站';
        
        // 文章列表
        $map = [
            'delete_time' => [
                'gt',
                0
            ]
        ];
        $this->assignArticleList($map);
        
        return $this->fetch();
    }

    /**
     * 赋值文章列表
     *
     * @param array $map            
     */
    protected function assignArticleList($map)
    {
        $request = Request::instance();
        
        // 查询条件-分类
        $cate = $request->param('cate');
        if (! empty($cate)) {
            $cate = intval($cate);
            $map['cate_id'] = $cate;
        }
        $this->assign('cate', $cate);
        
        // 查询条件-状态
        $status = $request->param('status', '');
        if ($status != '') {
            $status = intval($status);
            $map['article_status'] = $status;
        }
        $this->assign('status', $status);
        
        // 查询条件-关键词
        $keyword = $request->param('keyword');
        if ($keyword != '') {
            $map['article_title|article_author|article_content'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        // 分页列表
        $model = ArticleModel::getInstance();
        $query = $model->withCates($model);
        $query = $query->field('_a_article.id, article_key, article_title, article_author, article_cover, article_info, article_sort, article_status, _a_article.create_time, group_concat(cate_name) as cate_name')
            ->where($map)
            ->group('_a_article.id')
            ->order('article_sort desc, _a_article.id desc');
        $this->_page($model);
        
        // 分类列表
        $this->assignCateList();
        
        // 赋值状态列表
        $this->assignStatusList();
    }

    /**
     * 添加文章
     *
     * @param Request $request            
     * @return string
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'article_title' => $request->param('article_title'),
                'article_author' => $request->param('article_author'),
                'article_info' => $request->param('article_info'),
                'article_cover' => $request->param('article_cover'),
                'article_origin' => $request->param('article_origin'),
                'article_content' => $request->param('article_content'),
                'article_status' => $request->param('article_status', 0),
                'create_time' => strtotime($request->param('create_time')),
                'article_sort' => $request->param('article_sort', 0)
            ];
            $articleCates = $request->param('article_cate/a', []);
            $articleTags = $request->param('article_tags', '');
            
            // 验证
            $this->_validate(ArticleValidate::class, $data, 'add');
            
            // 添加
            $logic = ArticleLogic::getSingleton();
            $logic->addArticle($data, $articleCates, $articleTags);
            $this->success('新增成功', self::JUMP_REFERER);
        } else {
            $this->siteTitle = '新增文章';
            
            // 分类列表
            $this->assignCateList();
            
            // 状态列表
            $this->assignStatusList();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑文章
     *
     * @param Request $request            
     * @return mixed
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'article_title' => $request->param('article_title'),
                'article_author' => $request->param('article_author'),
                'article_info' => $request->param('article_info'),
                'article_cover' => $request->param('article_cover'),
                'article_origin' => $request->param('article_origin'),
                'article_sort' => $request->param('article_sort', 0),
                'article_content' => $request->param('article_content'),
                'create_time' => strtotime($request->param('create_time')),
                'article_status' => $request->param('article_status', 0)
            ];
            $articleCates = $request->param('article_cate/a', []);
            $articleTags = $request->param('article_tags', '');
            
            // 验证
            $this->_validate(ArticleValidate::class, $data, 'edit');
            
            // 修改
            $logic = ArticleLogic::getSingleton();
            $logic->saveArticle($data, $this->_id(), $articleCates, $articleTags);
            $this->success('修改成功', self::JUMP_REFERER);
        } else {
            $this->siteTitle = '编辑文章';
            
            // 记录
            $logic = ArticleLogic::getSingleton();
            $record = $logic->getRecord($this->_id());
            $this->assign('_record', $record);
            
            // 分类列表
            $this->assignCateList();
            
            // 状态列表
            $this->assignStatusList();
            
            return $this->fetch();
        }
    }

    /**
     * 更改文章
     *
     * @param Request $request            
     * @return mixed
     */
    public function modify(Request $request)
    {
        $fields = [
            'article_cate',
            'article_sort',
            'article_status',
            'delete_time'
        ];
        $this->_modify(ArticleModel::class, $fields);
    }

    /**
     * 删除文章
     *
     * @param Request $request            
     * @return mixed
     */
    public function delete(Request $request)
    {
        $this->_delete(ArticleModel::class, true);
    }

    /**
     * 赋值分类列表
     *
     * @return void
     */
    protected function assignCateList()
    {
        $model = ArticleCateModel::getInstance();
        $cateList = $model->getCateList();
        $this->assign('cate_list', $cateList);
    }

    /**
     * 赋值状态列表
     *
     * @return void
     */
    protected function assignStatusList()
    {
        $model = ArticleModel::getInstance();
        $statusList = $model->getStatusList();
        $this->assign('status_list', $statusList);
    }
}