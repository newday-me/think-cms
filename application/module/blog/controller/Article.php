<?php
namespace module\blog\controller;

use think\Request;
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
        
        // 查询条件
        $map = [
            'delete_time' => 0
        ];
        
        // 查询条件-分类
        $cate = $request->param('cate');
        if (! empty($cate)) {
            $cate = intval($cate);
            $map['article_cate'] = $cate;
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
        $model = ArticleModel::getSingleton();
        $model = $model->where($map)->order('article_sort desc');
        $this->_page($model);
        
        // 分类列表
        $this->assignCateList();
        
        // 赋值状态列表
        $this->assignStatusList();
        
        return $this->fetch();
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
                'article_cate' => $request->param('article_cate'),
                'article_author' => $request->param('article_author'),
                'article_tags' => $request->param('article_tags'),
                'article_info' => $request->param('article_info'),
                'article_cover' => $request->param('article_cover'),
                'article_origin' => $request->param('article_origin'),
                'article_content' => $request->param('article_content'),
                'article_status' => $request->param('article_status', 0),
                'create_time' => strtotime($request->param('create_time')),
                'article_sort' => $request->param('article_sort', 0)
            ];
            
            // 验证
            $this->_validate(ArticleValidate::class, $data, 'add');
            
            // 添加
            $this->_add(ArticleModel::class, $data);
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
                'article_cate' => $request->param('article_cate'),
                'article_tags' => $request->param('article_tags'),
                'article_origin' => $request->param('article_origin'),
                'article_sort' => $request->param('article_sort', 0),
                'article_content' => $request->param('article_content'),
                'create_time' => strtotime($request->param('create_time')),
                'article_status' => $request->param('article_status', 0)
            ];
            
            // 验证
            $this->_validate(ArticleValidate::class, $data, 'edit');
            
            // 修改
            $this->_edit(ArticleModel::class, $data);
        } else {
            $this->siteTitle = '编辑文章';
            
            // 记录
            $this->_record(ArticleModel::class);
            
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
            'article_status'
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
        $model = ArticleCateModel::getSingleton();
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
        $model = ArticleModel::getSingleton();
        $statusList = $model->getStatusList();
        $this->assign('status_list', $statusList);
    }
}