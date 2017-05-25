<?php
namespace module\blog\controller;

use think\Request;
use core\blog\model\ArticleCateModel;
use core\blog\model\ArticleCateLinkModel;
use core\blog\logic\ArticleCateLogic;
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
        
        $nest = ArticleCateLogic::getInstance()->getCateNest();
        $this->_list($nest['tree']);
        
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
                'cate_title' => $request->param('cate_title'),
                'cate_pid' => $request->param('cate_pid', 0),
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
            
            // 上级分类
            $pid = intval($request->param('pid', 0));
            $this->assign('pid', $pid);
            
            // 分类列表下拉
            $this->assignSelectCateList();
            
            // 分类状态下拉
            $this->assignSelectCateStatus();
            
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
                'cate_title' => $request->param('cate_title'),
                'cate_pid' => $request->param('cate_pid', 0),
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
            
            // 分类列表下拉
            $this->assignSelectCateList();
            
            // 分类状态下拉
            $this->assignSelectCateStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 分类排序
     *
     * @return void
     */
    public function sort()
    {
        $this->_sort(ArticleCateModel::class, 'cate_sort');
    }

    /**
     * 删除分类
     *
     * @param Request $request            
     * @return mixed
     */
    public function delete(Request $request)
    {
        // 检查关联文章数
        $map = [
            'cate_id' => $this->_id()
        ];
        $count = ArticleCateLinkModel::getInstance()->where($map)->count();
        $count && $this->error('有' . $count . '篇文章与该分类关联');
        
        $this->_delete(ArticleCateModel::class, false);
    }

    /**
     * 赋值分类列表下拉
     *
     * @return void
     */
    protected function assignSelectCateList()
    {
        $selectCateList = ArticleCateLogic::getSingleton()->getSelectList();
        $this->assign('select_cate_list', $selectCateList);
    }

    /**
     * 赋值分类状态下拉
     *
     * @return void
     */
    protected function assignSelectCateStatus()
    {
        $selectCateStatus = ArticleCateLogic::getSingleton()->getSelectStatus();
        $this->assign('select_cate_status', $selectCateStatus);
    }
}
