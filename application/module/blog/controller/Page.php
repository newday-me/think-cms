<?php
namespace module\blog\controller;

use think\Request;
use core\blog\model\PageModel;
use core\blog\logic\PageLogic;
use core\blog\validate\PageValidate;

class Page extends Base
{

    /**
     * 页面列表
     *
     * @param Request $request            
     *
     * @return void
     */
    public function index(Request $request)
    {
        $this->siteTitle = '页面列表';
        
        // 页面列表
        $map = [
            'delete_time' => 0
        ];
        $this->assignPageList($map);
        
        return $this->fetch();
    }

    /**
     * 页面回收站
     *
     * @param Request $request            
     *
     * @return void
     */
    public function recycle(Request $request)
    {
        $this->siteTitle = '页面回收站';
        
        // 页面列表
        $map = [
            'delete_time' => [
                'gt',
                0
            ]
        ];
        $this->assignPageList($map);
        
        return $this->fetch();
    }

    /**
     * 添加页面
     *
     * @param Request $request            
     * @return string
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'page_title' => $request->param('page_title'),
                'page_name' => $request->param('page_name'),
                'page_content' => $request->param('page_content'),
                'page_sort' => $request->param('page_sort', 0),
                'page_status' => $request->param('page_status', 0)
            ];
            
            // 验证
            $this->_validate(PageValidate::class, $data, 'add');
            
            // 添加
            $this->_add(PageModel::class, $data);
        } else {
            $this->siteTitle = '新增页面';
            
            // 状态下拉
            $this->assignSelectPageStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑页面
     *
     * @param Request $request            
     * @return mixed
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'page_title' => $request->param('page_title'),
                'page_name' => $request->param('page_name'),
                'page_content' => $request->param('page_content'),
                'page_sort' => $request->param('page_sort', 0),
                'page_status' => $request->param('page_status', 0)
            ];
            
            // 验证
            $this->_validate(PageValidate::class, $data, 'edit');
            
            // 修改
            $this->_edit(PageModel::class, $data);
        } else {
            $this->siteTitle = '编辑页面';
            
            // 记录
            $this->_record(PageModel::class);
            
            // 状态下拉
            $this->assignSelectPageStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 更改页面
     *
     * @param Request $request            
     * @return mixed
     */
    public function modify(Request $request)
    {
        $fields = [
            'page_sort',
            'page_status'
        ];
        $this->_modify(PageModel::class, $fields);
    }

    /**
     * 删除页面
     *
     * @param Request $request            
     * @return mixed
     */
    public function delete(Request $request)
    {
        $this->_delete(PageModel::class, true);
    }

    /**
     * 恢复页面
     *
     * @param Request $request            
     * @return mixed
     */
    public function recover(Request $request)
    {
        $this->_recover(PageModel::class);
    }

    /**
     * 赋值页面列表
     *
     * @param array $map            
     *
     * @return void
     */
    protected function assignPageList($map = [])
    {
        $request = Request::instance();
        
        // 查询条件-状态
        $status = $request->param('status', '');
        if ($status != '') {
            $status = intval($status);
            $map['page_status'] = $status;
        }
        $this->assign('status', $status);
        
        // 查询条件-关键词
        $keyword = $request->param('keyword');
        if ($keyword != '') {
            $map['page_name|page_content'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        // 列表
        $model = PageModel::getInstance()->where($map)->order('page_sort desc');
        $this->_page($model);
        
        // 状态下拉
        $this->assignSelectPageStatus();
    }

    /**
     * 赋值页面状态下拉
     *
     * @return void
     */
    protected function assignSelectPageStatus()
    {
        $selectPageStatus = PageLogic::getSingleton()->getSelectStatus();
        $this->assign('select_page_status', $selectPageStatus);
    }
}