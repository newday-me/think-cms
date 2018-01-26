<?php

namespace app\manage\controller;

use think\facade\Url;
use app\manage\service\MenuService;

class Menu extends Base
{

    /**
     * 菜单管理
     *
     * @return string
     */
    public function index()
    {
        $this->assign('site_title', '菜单管理');

        // 菜单树
        $menuTree = MenuService::getSingleton()->getMenuTree();
        $this->assign('menu_tree_base', base64_encode(json_encode($menuTree)));

        // 操作
        $actionList = [
            'add' => Url::build('add'),
            'edit' => Url::build('edit'),
            'modify' => Url::build('modify'),
            'drag' => Url::build('drag'),
            'delete' => Url::build('delete')
        ];
        $this->assign('action_list_json', json_encode($actionList));

        return $this->fetch();
    }

    /**
     * 新增菜单
     */
    public function add()
    {
        $data = [
            'menu_pno' => $this->request->param('menu_pno', ''),
            'menu_name' => $this->request->param('menu_name'),
            'menu_url' => $this->request->param('menu_url', ''),
            'menu_icon' => $this->request->param('menu_icon', ''),
            'menu_type' => $this->request->param('menu_type'),
            'menu_build' => $this->request->param('menu_build'),
            'menu_target' => $this->request->param('menu_target'),
            'menu_show' => $this->request->param('menu_show')
        ];
        $return = MenuService::getSingleton()->createMenu($data);
        $this->response($return);
    }

    /**
     * 编辑菜单
     */
    public function edit()
    {
        $menuNo = $this->request->param('data_no');
        if (empty($menuNo)) {
            $this->error('菜单编号为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $return = MenuService::getSingleton()->getMenu($menuNo);
                $this->response($return);
                break;
            case 'save':
                $data = [
                    'menu_name' => $this->request->param('menu_name'),
                    'menu_url' => $this->request->param('menu_url', ''),
                    'menu_icon' => $this->request->param('menu_icon', ''),
                    'menu_type' => $this->request->param('menu_type'),
                    'menu_build' => $this->request->param('menu_build'),
                    'menu_target' => $this->request->param('menu_target'),
                    'menu_show' => $this->request->param('menu_show')
                ];
                $return = MenuService::getSingleton()->updateMenu($menuNo, $data);
                $this->response($return);
                break;
            default:
                $this->error('未知操作');
        }
    }

    /**
     * 更改菜单
     */
    public function modify()
    {
        $menuNo = $this->request->param('data_no');
        if (empty($menuNo)) {
            $this->error('菜单编号为空');
        }

        $field = $this->request->param('field');
        if (empty($field)) {
            $this->error('字段名为空');
        }

        $value = $this->request->param('value');
        if (is_null($value)) {
            $this->error('值为空');
        }

        $return = MenuService::getSingleton()->modifyMenu($menuNo, $field, $value);
        $this->response($return);
    }

    /**
     * 拖拽菜单
     */
    public function drag()
    {
        $mode = $this->request->param('mode');

        $fromNo = $this->request->param('from_no');
        if (empty($fromNo)) {
            $this->error('来源菜单编号为空');
        }

        $toNo = $this->request->param('to_no');
        if (empty($toNo)) {
            $this->error('目标菜单编号为空');
        }

        $return = MenuService::getSingleton()->dragMenu($mode, $fromNo, $toNo);
        $this->response($return);
    }

    /**
     * 删除菜单
     */
    public function delete()
    {
        $menuNo = $this->request->param('data_no');
        if (empty($menuNo)) {
            $this->error('菜单编号为空');
        }

        $return = MenuService::getSingleton()->deleteMenu($menuNo);
        $this->response($return);
    }

}