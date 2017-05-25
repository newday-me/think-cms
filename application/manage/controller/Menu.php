<?php
namespace app\manage\controller;

use think\Request;
use core\manage\logic\MenuLogic;
use core\manage\model\MenuModel;
use core\manage\validate\MenuValidate;

class Menu extends Base
{

    /**
     * 菜单列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '菜单管理';
        
        // 记录列表
        $list = MenuLogic::getInstance()->getMenuNest();
        $this->_list($list['tree']);
        
        return $this->fetch();
    }

    /**
     * 添加菜单
     *
     * @param Request $request            
     * @return string|void
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'menu_name' => $request->param('menu_name'),
                'menu_url' => $request->param('menu_url'),
                'menu_pid' => $request->param('menu_pid', 0),
                'menu_sort' => $request->param('menu_sort', 0),
                'menu_target' => $request->param('menu_target', ''),
                'menu_build' => $request->param('menu_build', 0),
                'menu_status' => $request->param('menu_status', 0)
            ];
            
            // 验证
            $this->_validate(MenuValidate::class, $data, 'add');
            
            // 处理数据
            $logic = MenuLogic::getSingleton();
            $data = $logic->processMenuData($data);
            
            // 添加
            $this->_add(MenuModel::class, $data);
        } else {
            $this->siteTitle = '新增菜单';
            
            // pid
            $pid = $request->param('pid', 0);
            $this->assign('pid', intval($pid));
            
            // 分组名称
            $group = $request->param('group', '');
            $this->assign('group', $group);
            
            // 上级菜单
            $this->assignMenuList();
            
            // 菜单打开方式下拉
            $this->assignSelectMenuTarget();
            
            // 菜单链接build下拉
            $this->assignSelectMenuBuild();
            
            // 菜单状态下拉
            $this->assignSelectMenuStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑菜单
     *
     * @param Request $request            
     * @return string|void
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'menu_name' => $request->param('menu_name'),
                'menu_url' => $request->param('menu_url'),
                'menu_pid' => $request->post('menu_pid', 0),
                'menu_sort' => $request->param('menu_sort', 0),
                'menu_target' => $request->param('menu_target', ''),
                'menu_build' => $request->param('menu_build', 0),
                'menu_status' => $request->param('menu_status', 0)
            ];
            
            // 验证
            $this->_validate(MenuValidate::class, $data, 'edit');
            
            // 处理数据
            $logic = MenuLogic::getSingleton();
            $data = $logic->processMenuData($data);
            
            // 修改
            $this->_edit(MenuModel::class, $data);
        } else {
            $this->siteTitle = '编辑菜单';
            
            // 记录
            $this->_record(MenuModel::class);
            
            // 上级菜单
            $this->assignMenuList();
            
            // 菜单打开方式下拉
            $this->assignSelectMenuTarget();
            
            // 菜单链接build下拉
            $this->assignSelectMenuBuild();
            
            // 菜单状态下拉
            $this->assignSelectMenuStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 更改菜单
     *
     * @return void
     */
    public function modify()
    {
        $fields = [
            'menu_sort',
            'menu_status'
        ];
        $this->_modify(MenuModel::class, $fields);
    }

    /**
     * 菜单排序
     *
     * @return void
     */
    public function sort()
    {
        $this->_sort(MenuModel::class, 'menu_sort');
    }

    /**
     * 删除菜单
     *
     * @return void
     */
    public function delete()
    {
        $model = MenuModel::getInstance();
        $map = [
            'menu_pid' => $this->_id()
        ];
        if ($model->where($map)->find()) {
            $this->error('请先删除该菜单下的子菜单');
        }
        
        $this->_delete(MenuModel::class, false);
    }

    /**
     * 赋值菜单列表
     *
     * @return void
     */
    protected function assignMenuList()
    {
        $selectMenuList = MenuLogic::getSingleton()->getSelectList();
        $this->assign('select_menu_list', $selectMenuList);
    }

    /**
     * 赋值菜单打开方式下拉
     *
     * @return void
     */
    protected function assignSelectMenuTarget()
    {
        $selectMenuTarget = MenuLogic::getSingleton()->getSelectTarget();
        $this->assign('select_menu_target', $selectMenuTarget);
    }

    /**
     * 赋值菜单链接Build下拉
     *
     * @return void
     */
    protected function assignSelectMenuBuild()
    {
        $selectMenuBuild = MenuLogic::getSingleton()->getSelectBuild();
        $this->assign('select_menu_build', $selectMenuBuild);
    }

    /**
     * 赋值菜单状态下拉
     *
     * @return void
     */
    protected function assignSelectMenuStatus()
    {
        $selectMenuStatus = MenuLogic::getSingleton()->getSelectStatus();
        $this->assign('select_menu_status', $selectMenuStatus);
    }
}