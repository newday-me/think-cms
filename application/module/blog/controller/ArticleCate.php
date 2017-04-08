<?php
namespace module\blog\controller;

use think\Request;
use core\blog\model\ArticleModel;
use core\blog\model\ArticleCateModel;
use core\blog\validate\ArticleCateValidate;

class ArticleCate extends Base
{

    /**
     * 分类列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '分类列表';
        
        // 分页列表
        $model = ArticleCateModel::getSingleton()->order('cate_sort asc');
        $this->_page($model);
        
        // 状态列表
        $this->assignStatusList();
        
        return $this->fetch();
    }

    /**
     * 添加分类
     *
     * @param Request $request            
     * @return mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'cate_name' => $request->param('cate_name'),
                'cate_flag' => $request->param('cate_flag'),
                'cate_info' => $request->param('cate_info'),
                'cate_sort' => $request->param('cate_sort', 0),
                'cate_status' => $request->param('cate_status', 0)
            ];
            
            // 验证
            $this->_validate(ArticleCateValidate::class, $data, 'add');
            
            // 添加
            $this->_add(ArticleCateModel::class, $data);
        } else {
            $this->siteTitle = '新增分类';
            
            // 状态列表
            $this->assignStatusList();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑分类
     *
     * @param Request $request            
     * @return mixed
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'cate_name' => $request->param('cate_name'),
                'cate_flag' => $request->param('cate_flag'),
                'cate_info' => $request->param('cate_info'),
                'cate_sort' => $request->param('cate_sort', 0),
                'cate_status' => $request->param('cate_status', 0)
            ];
            
            // 验证
            $this->_validate(ArticleCateValidate::class, $data, 'edit');
            
            // 修改
            $this->_edit(ArticleCateModel::class, $data);
        } else {
            $this->siteTitle = '编辑分类';
            
            // 记录
            $this->_record(ArticleCateModel::class);
            
            // 状态列表
            $this->assignStatusList();
            
            return $this->fetch();
        }
    }

    /**
     * 更改分类
     *
     * @param Request $request            
     * @return mixed
     */
    public function modify(Request $request)
    {
        $fields = [
            'cate_sort',
            'cate_status'
        ];
        $this->_modify(ArticleCateModel::class, $fields);
    }

    /**
     * 删除分类
     *
     * @param Request $request            
     * @return mixed
     */
    public function delete(Request $request)
    {
        $model = ArticleModel::getSingleton();
        $map = [
            'article_cate' => $this->_id()
        ];
        if ($model->where($map)->find()) {
            $this->error('请先删除该分类下的文章');
        }
        
        $this->_delete($model, false);
    }

    /**
     * 赋值状态列表
     *
     * @return void
     */
    protected function assignStatusList()
    {
        $model = ArticleCateModel::getSingleton();
        $statusList = $model->getStatusList();
        $this->assign('status_list', $statusList);
    }
}
