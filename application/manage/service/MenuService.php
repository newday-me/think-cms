<?php

namespace app\manage\service;

use think\facade\Url;
use core\base\Service;
use core\logic\support\TreeLogic;
use app\manage\logic\MenuLogic;
use app\manage\logic\WidgetLogic;
use core\db\manage\data\ManageMenuData;
use core\db\manage\validate\ManageMenuValidate;
use core\db\manage\constant\ManageMenuConstant;

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
     * @return array|null
     */
    public function getMenu($menuNo)
    {
        $this->resetError();

        $menu = ManageMenuData::getSingleton()->getMenu($menuNo);
        if ($menu) {
            return [
                'menu_no' => $menu['menu_no'],
                'menu_name' => $menu['menu_name'],
                'menu_icon' => $menu['menu_icon'],
                'menu_url' => $menu['menu_url'],
                'menu_build' => $menu['menu_build'],
                'menu_target' => $menu['menu_target'],
                'menu_type' => $menu['menu_type'],
                'menu_status' => $menu['menu_status'],
            ];
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '菜单不存在');
            return null;
        }
    }

    /**
     * 创建菜单
     *
     * @param array $data
     * @return bool|null
     */
    public function createMenu($data)
    {
        $this->resetError();

        $validate = ManageMenuValidate::getSingleton();
        if (!$validate->scene('add')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        // 菜单操作
        $data['menu_action'] = MenuLogic::getSingleton()->parseMenuAction($data['menu_url'], $data['menu_build']);

        $menu = ManageMenuData::getSingleton()->createMenu($data);
        if ($menu) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '创建菜单失败');
            return null;
        }
    }

    /**
     * 保存菜单
     *
     * @param string $menuNo
     * @param array $data
     * @return bool|null
     */
    public function updateMenu($menuNo, $data)
    {
        $this->resetError();

        $validate = ManageMenuValidate::getSingleton();
        if (!$validate->scene('edit')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        // 菜单操作
        $data['menu_action'] = MenuLogic::getSingleton()->parseMenuAction($data['menu_url'], $data['menu_build']);

        if (ManageMenuData::getSingleton()->updateMenu($menuNo, $data)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '更新菜单失败');
            return null;
        }
    }

    /**
     *  更改菜单
     *
     * @param string $menuNo
     * @param string $field
     * @param string $value
     * @return bool|null
     */
    public function modifyMenu($menuNo, $field, $value)
    {
        $this->resetError();

        $allowField = [
            'menu_type',
            'menu_status'
        ];
        if (!in_array($field, $allowField)) {
            $this->setError(self::ERROR_CODE_DEFAULT, '非法的字段名');
            return null;
        }

        $data = [
            $field => $value
        ];
        if (ManageMenuData::getSingleton()->updateMenu($menuNo, $data)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '更新菜单失败');
            return null;
        }
    }

    /**
     * 移动菜单
     *
     * @param string $mode
     * @param string $fromNo
     * @param string $toNo
     * @return bool|null
     */
    public function dragMenu($mode, $fromNo, $toNo)
    {
        $this->resetError();

        $result = MenuLogic::getSingleton()->drag($mode, $fromNo, $toNo);
        if ($result) {
            return true;
        } else {
            $this->setErrorByObject(MenuLogic::getSingleton());
            return null;
        }
    }

    /**
     * 删除菜单
     *
     * @param string $menuNo
     * @return bool|null
     */
    public function deleteMenu($menuNo)
    {
        $this->resetError();

        $menuCount = ManageMenuData::getSingleton()->getSubMenuCount($menuNo);
        if ($menuCount) {
            $this->setError(self::ERROR_CODE_DEFAULT, '请先删除该菜单下的子菜单');
            return null;
        }

        if (ManageMenuData::getSingleton()->deleteMenu($menuNo)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '删除菜单失败');
            return null;
        }
    }

}