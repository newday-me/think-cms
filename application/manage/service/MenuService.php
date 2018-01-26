<?php

namespace app\manage\service;

use think\facade\Url;
use core\base\Service;
use core\logic\support\TreeLogic;
use core\db\manage\data\ManageMenuData;
use core\db\manage\validate\ManageMenuValidate;
use core\db\manage\constant\ManageMenuConstant;
use app\manage\logic\MenuLogic;
use app\manage\logic\WidgetLogic;

class MenuService extends Service
{

    /**
     * 获取菜单树
     *
     * @return array
     */
    public function getMenuTree()
    {
        $menuTree = MenuLogic::getSingleton()->getMenuTree();

        // 遍历菜单树
        $widget = WidgetLogic::getSingleton()->getWidget();
        $modifyUrl = Url::build('manage/menu/modify');
        TreeLogic::getSingleton()->travelTree($menuTree, function (&$vo) use ($widget, $modifyUrl) {
            $vo['menu_type_html'] = $widget->table('radio', [
                'value' => $vo['menu_type'],
                'list' => [
                    [
                        'name' => '菜单',
                        'value' => ManageMenuConstant::TYPE_MENU
                    ],
                    [
                        'name' => '行为',
                        'value' => ManageMenuConstant::TYPE_ACTION
                    ]
                ],
                'field' => 'menu_type',
                'url' => $modifyUrl,
                'data_no' => $vo['menu_no']
            ]);

            $vo['menu_status_html'] = $widget->table('switch', [
                'value' => $vo['menu_status'],
                'on' => ManageMenuConstant::STATUS_ENABLE,
                'off' => ManageMenuConstant::STATUS_DISABLE,
                'field' => 'menu_status',
                'url' => $modifyUrl,
                'data_no' => $vo['menu_no']
            ]);
        });

        return $menuTree;
    }

    /**
     * 获取侧边菜单
     *
     * @param string $userNo
     * @return array
     */
    public function getSideMenu($userNo)
    {
        return MenuLogic::getSingleton()->getSideMenu($userNo);
    }

    /**
     * 获取菜单
     *
     * @param string $menuNo
     * @return \cms\core\objects\ReturnObject
     */
    public function getMenu($menuNo)
    {
        $menu = ManageMenuData::getSingleton()->getMenu($menuNo);
        if ($menu) {
            return $this->returnSuccess('获取成功', [
                'menu_no' => $menu['menu_no'],
                'menu_name' => $menu['menu_name'],
                'menu_icon' => $menu['menu_icon'],
                'menu_url' => $menu['menu_url'],
                'menu_build' => $menu['menu_build'],
                'menu_target' => $menu['menu_target'],
                'menu_type' => $menu['menu_type'],
                'menu_status' => $menu['menu_status'],
            ]);
        } else {
            return $this->returnError('菜单不存在');
        }
    }

    /**
     * 创建菜单
     *
     * @param array $data
     * @return \cms\core\objects\ReturnObject
     */
    public function createMenu($data)
    {
        $validate = ManageMenuValidate::getSingleton();
        if (!$validate->scene('add')->check($data)) {
            return $this->returnError($validate->getError());
        }

        // 菜单操作
        $data['menu_action'] = MenuLogic::getSingleton()->parseMenuAction($data['menu_url'], $data['menu_build']);

        $menu = ManageMenuData::getSingleton()->createMenu($data);
        if ($menu) {
            return $this->returnSuccess('创建成功');
        } else {
            return $this->returnError('创建失败');
        }
    }

    /**
     * 保存菜单
     *
     * @param string $menuNo
     * @param array $data
     * @return \cms\core\objects\ReturnObject
     */
    public function updateMenu($menuNo, $data)
    {
        $validate = ManageMenuValidate::getSingleton();
        if (!$validate->scene('edit')->check($data)) {
            return $this->returnError($validate->getError());
        }

        // 菜单操作
        $data['menu_action'] = MenuLogic::getSingleton()->parseMenuAction($data['menu_url'], $data['menu_build']);

        if (ManageMenuData::getSingleton()->updateMenu($menuNo, $data)) {
            return $this->returnSuccess('保存成功');
        } else {
            return $this->returnSuccess('保存失败');
        }
    }

    /**
     *  更改菜单
     *
     * @param string $menuNo
     * @param string $field
     * @param string $value
     * @return \cms\core\objects\ReturnObject
     */
    public function modifyMenu($menuNo, $field, $value)
    {
        $allowField = [
            'menu_type',
            'menu_status'
        ];
        if (!in_array($field, $allowField)) {
            return $this->returnError('非法的字段名');
        }

        $data = [
            $field => $value
        ];
        if (ManageMenuData::getSingleton()->updateMenu($menuNo, $data)) {
            return $this->returnSuccess('操作成功');
        } else {
            return $this->returnError('保存失败');
        }
    }

    /**
     * 移动菜单
     *
     * @param string $mode
     * @param string $fromNo
     * @param string $toNo
     * @return \cms\core\objects\ReturnObject
     */
    public function dragMenu($mode, $fromNo, $toNo)
    {
        return MenuLogic::getSingleton()->drag($mode, $fromNo, $toNo);
    }

    /**
     * 删除菜单
     *
     * @param string $menuNo
     * @return \cms\core\objects\ReturnObject
     */
    public function deleteMenu($menuNo)
    {
        $menuCount = ManageMenuData::getSingleton()->getSubMenuCount($menuNo);
        if ($menuCount) {
            return $this->returnError('请先删除该菜单下的子菜单');
        }

        if (ManageMenuData::getSingleton()->deleteMenu($menuNo)) {
            return $this->returnSuccess('删除成功');
        } else {
            return $this->returnError('删除失败');
        }
    }

}