<?php

namespace app\manage\logic;

use core\db\manage\constant\ManageMenuConstant;
use core\logic\support\DragLogic;
use think\facade\Url;
use cms\support\Util;
use core\db\manage\data\ManageMenuData;
use core\db\manage\data\ManageUserData;
use core\logic\support\TreeLogic;

class MenuLogic extends DragLogic
{

    /**
     * 获取菜单树
     *
     * @return array
     */
    public function getMenuTree()
    {
        $menuList = ManageMenuData::getSingleton()->getMenuList();
        $menuTree = TreeLogic::getSingleton()->buildTree($menuList, 'menu_no', 'menu_pno', ManageMenuConstant::ROOT_PNO_VALUE);

        // 遍历菜单树
        TreeLogic::getSingleton()->travelTree($menuTree, function (&$vo, $depth) {
            $vo['expanded'] = $depth < 2;
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
        $menuList = ManageUserData::getSingleton()->getUserMenuList($userNo);
        $menuTree = TreeLogic::getSingleton()->buildTree($menuList, 'menu_no', 'menu_pno', ManageMenuConstant::ROOT_PNO_VALUE);

        // 激活当前菜单
        $currentMenu = $this->getCurrentMenu();
        if ($currentMenu) {
            if ($currentMenu['menu_type'] == ManageMenuConstant::TYPE_MENU) {
                $this->activeSideMenu($menuTree, $currentMenu['menu_no']);
            } else {
                $this->activeSideMenu($menuTree, $currentMenu['menu_pno']);
            }
        }

        // 遍历菜单树
        TreeLogic::getSingleton()->travelTree($menuTree, function (&$vo) {
            $item = [
                'menu_no' => $vo['menu_no'],
                'menu_name' => $vo['menu_name'],
                'menu_icon' => $vo['menu_icon'] ? $vo['menu_icon'] : 'fa fa-circle-o',
                'menu_url' => $vo['menu_url'],
                'menu_link' => $vo['menu_build'] ? Url::build($vo['menu_url']) : $vo['menu_url'],
                'menu_target' => $vo['menu_target'],
                'menu_active' => isset($vo['menu_active']) ? $vo['menu_active'] : false
            ];
            // 是否有子菜单
            if (isset($vo['children'])) {
                $item['children'] = $vo['children'];
            }
            $vo = $item;
        });

        return $menuTree;
    }

    /**
     * 激活侧边菜单
     *
     * @param array $menuTree
     * @param string $menuNo
     * @return bool
     */
    public function activeSideMenu(&$menuTree, $menuNo)
    {
        foreach ($menuTree as &$vo) {
            if (isset($vo['children'])) {
                $menuActive = $this->activeSideMenu($vo['children'], $menuNo);
                if ($menuActive) {
                    $vo['menu_active'] = true;
                    return true;
                }
            }

            if ($vo['menu_no'] == $menuNo) {
                $vo['menu_active'] = true;
                return true;
            }
        }
        return false;
    }

    /**
     * 解析菜单操作
     *
     * @param string $link
     * @param boolean $build
     * @return string
     */
    public function parseMenuAction($link, $build = true)
    {
        // 外链
        if ($build == false || empty($link)) {
            return '';
        }

        // 测试连接
        $urlTest = 'path/test/domain';
        $urlPath = str_replace($urlTest . '.html', '', Url::build($urlTest, '', true, true));

        // 相对url
        $url = Url::build($link, '', true, true);
        $urlRelative = str_replace([
            $urlPath,
            '.html'
        ], '', $url);

        // Url标识
        $arr = explode('/', $urlRelative);
        if (strpos($link, '@module') !== false) {
            $arr = array_slice($arr, 0, 4);
        } else {
            $arr = array_slice($arr, 0, 3);
        }
        return implode('/', $arr);
    }

    /**
     * 获取当前菜单
     *
     * @return array|null
     */
    public function getCurrentMenu()
    {
        $menuAction = $this->getCurrentAction();
        return ManageMenuData::getSingleton()->getMenuByMenuAction($menuAction);
    }

    /**
     * 获取当前操作
     *
     * @return string
     */
    public function getCurrentAction()
    {
        if (defined('_MODULE_')) {
            return 'module' . '/' . _MODULE_ . '/' . _CONTROLLER_ . '/' . _ACTION_;
        } else {
            return Util::getSingleton()->getCurrentAction();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DragLogic::onDragOver()
     */
    public function onDragOver($fromNo, $toNo)
    {
        $manageMenuData = ManageMenuData::getSingleton();
        $menuSort = $manageMenuData->getMaxMenuSort($toNo);
        $data = [
            'menu_pno' => $toNo,
            'menu_sort' => $menuSort + 1
        ];
        if ($manageMenuData->updateMenu($fromNo, $data)) {
            return $this->returnSuccess('操作成功');
        } else {
            return $this->returnError('操作失败');
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DragLogic::onDragSide()
     */
    public function onDragSide($before, $fromNo, $toNo)
    {
        $manageMenuData = ManageMenuData::getSingleton();

        // 查找目标菜单
        $toMenu = $manageMenuData->getMenu($toNo);
        if (empty($toMenu)) {
            return $this->returnError('目标菜单不存在');
        }

        // 更新上级菜单
        $manageMenuData->updateMenuPno($fromNo, $toMenu['menu_pno']);

        // 更新菜单排序
        $menuSort = 0;
        $menuList = $manageMenuData->getSubMenuList($toMenu['menu_pno']);
        foreach ($menuList as $vo) {
            if ($vo['menu_no'] == $toNo) {
                if ($before) {
                    $manageMenuData->updateMenuSort($fromNo, $menuSort++);
                    $manageMenuData->updateMenuSort($toNo, $menuSort++);
                } else {
                    $manageMenuData->updateMenuSort($toNo, $menuSort++);
                    $manageMenuData->updateMenuSort($fromNo, $menuSort++);
                }
            } elseif ($vo['menu_no'] != $fromNo) {
                $manageMenuData->updateMenuSort($vo['menu_no'], $menuSort++);
            }
        }

        return $this->returnSuccess('操作成功');
    }

}