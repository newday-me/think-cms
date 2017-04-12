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
        
        // 上级
        $pid = $request->param('pid', 0);
        $this->assign('pid', $pid);
        
        // 查询条件
        $map = [
            'menu_pid' => $pid
        ];
        
        // 查询条件-分组
        $group = $request->param('group', '');
        if (! empty($group_name)) {
            $map['menu_group'] = $group;
        }
        $this->assign('group', $group);
        
        // 记录列表
        $list = MenuModel::getInstance()->where($map)
            ->order('menu_sort asc')
            ->select();
        $this->_list($list);
        
        // 分组列表
        $this->assignGroupList([
            'menu_pid' => $pid
        ]);
        
        // 状态列表
        $this->assignStatusList();
        
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
                'menu_group' => $request->param('menu_group', ''),
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
            
            // 上级，返回用
            $pid = $request->param('pid', 0);
            $this->assign('pid', intval($pid));
            
            // 上级菜单
            $this->assignMenuList();
            
            // 打开方式
            $this->assignTargetList();
            
            // 是否build
            $this->assignBuildList();
            
            // 菜单状态
            $this->assignStatusList();
            
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
                'menu_group' => $request->param('menu_group', ''),
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
            
            // 打开方式
            $this->assignTargetList();
            
            // 是否build
            $this->assignBuildList();
            
            // 菜单状态
            $this->assignStatusList();
            
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
     * 赋值配置分组
     *
     * @return void
     */
    protected function assignGroupList($map = [])
    {
        $model = MenuModel::getInstance();
        $groupList = $model->getGroupList($map);
        $this->assign('group_list', $groupList);
    }

    /**
     * 赋值菜单列表
     *
     * @return void
     */
    protected function assignMenuList()
    {
        $logic = MenuLogic::getSingleton();
        $menuList = $logic->getMenuList();
        $this->assign('menu_list', $menuList);
    }

    /**
     * 赋值打开方式列表
     *
     * @return void
     */
    protected function assignTargetList()
    {
        $model = MenuModel::getInstance();
        $targetList = $model->getTargetList();
        $this->assign('target_list', $targetList);
    }

    /**
     * 赋值链接Build列表
     *
     * @return void
     */
    protected function assignBuildList()
    {
        $model = MenuModel::getInstance();
        $buildList = $model->getBuildList();
        $this->assign('build_list', $buildList);
    }

    /**
     * 赋值状态列表
     *
     * @return void
     */
    protected function assignStatusList()
    {
        $model = MenuModel::getInstance();
        $statusList = $model->getStatusList();
        $this->assign('status_list', $statusList);
    }
}