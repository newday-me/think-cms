<?php

namespace core\db\manage\data;

use core\base\Data;
use core\db\manage\model\ManageMenuModel;

class ManageMenuData extends Data
{

    /**
     * 创建菜单
     *
     * @param array $data
     * @return array|null
     */
    public function createMenu($data)
    {
        $record = ManageMenuModel::create($data);
        return $this->transToArray($record);
    }

    /**
     * 获取菜单
     *
     * @param string $menuNo
     * @return array|null
     * @throws
     */
    public function getMenu($menuNo)
    {
        $map = [
            'menu_no' => $menuNo
        ];
        $record = ManageMenuModel::getInstance()->get($map);
        return $this->transToArray($record);
    }

    /**
     * 根据操作获取菜单
     *
     * @param string $menuAction
     * @return array|null
     * @throws
     */
    public function getMenuByMenuAction($menuAction)
    {
        $map = [
            'menu_action' => $menuAction
        ];
        $record = ManageMenuModel::getInstance()->get($map);
        return $this->transToArray($record);
    }

    /**
     * 获取菜单列表
     *
     * @return array
     * @throws
     */
    public function getMenuList()
    {
        $list = ManageMenuModel::getInstance()->order('menu_sort asc')->select();
        return $this->transToArray($list);
    }

    /**
     * 获取子菜单列表
     *
     * @param string $menuNo
     * @return array
     * @throws
     */
    public function getSubMenuList($menuNo)
    {
        $map = [
            'menu_pno' => $menuNo
        ];
        $list = ManageMenuModel::getInstance()->where($map)->order('menu_sort asc')->select();
        return $this->transToArray($list);
    }

    /**
     * 获取子菜单数量
     *
     * @param string $menuNo
     * @return int|string
     */
    public function getSubMenuCount($menuNo)
    {
        $map = [
            'menu_pno' => $menuNo
        ];
        return ManageMenuModel::getSingleton()->where($map)->count();
    }

    /**
     * 获取最大排序值
     *
     * @param string $menuPno
     * @return int
     */
    public function getMaxMenuSort($menuPno)
    {
        $map = [
            'menu_pno' => $menuPno
        ];
        $menuSort = ManageMenuModel::getInstance()->where($map)->max('menu_sort');
        return intval($menuSort);
    }

    /**
     * 更新菜单
     *
     * @param string $menuNo
     * @param array $data
     * @return false|int
     */
    public function updateMenu($menuNo, $data)
    {
        $map = [
            'menu_no' => $menuNo
        ];
        return ManageMenuModel::getInstance()->save($data, $map);
    }

    /**
     * 更新上级菜单
     *
     * @param $menuNo
     * @param $menuPno
     * @return false|int
     */
    public function updateMenuPno($menuNo, $menuPno)
    {
        $data = [
            'menu_pno' => $menuPno
        ];
        return $this->updateMenu($menuNo, $data);
    }

    /**
     * 更新菜单排序
     *
     * @param string $menuNo
     * @param int $menuSort
     * @return false|int
     */
    public function updateMenuSort($menuNo, $menuSort)
    {
        $data = [
            'menu_sort' => $menuSort
        ];
        return $this->updateMenu($menuNo, $data);
    }

    /**
     * 删除菜单
     *
     * @param string $menuNo
     * @return int
     * @throws
     */
    public function deleteMenu($menuNo)
    {
        $map = [
            'menu_no' => $menuNo
        ];
        return ManageMenuModel::getInstance()->where($map)->delete();
    }

}