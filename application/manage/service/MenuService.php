<?php
namespace app\manage\service;

use think\Url;
use cms\Service;
use cms\Common;
use core\manage\logic\MenuLogic;
use core\manage\logic\UserGroupLogic;

class MenuService extends Service
{

    /**
     * 获取菜单Nest
     *
     * @return array
     */
    public function getMenuNest()
    {
        static $nest;
        if (is_null($nest)) {
            $user = LoginService::getSingleton()->getLoginUser();
            $menuIds = UserGroupLogic::getInstance()->getGroupMenuIds($user['user_gid']);
            
            $map = [
                'id' => [
                    'in',
                    $menuIds
                ]
            ];
            $nest = MenuLogic::getSingleton()->getMenuNest($map);
        }
        return $nest;
    }

    /**
     * 获取菜单树
     *
     * @return array
     */
    public function getMenuTree()
    {
        $nest = $this->getMenuNest();
        
        $menuTree = [];
        foreach ($nest['tree'] as $co => $vo) {
            if (! isset($menuTree[$co])) {
                $menuTree[$co] = [];
            }
            
            foreach ($vo as $ko) {
                $menuTree[$co][] = $this->parseMenuItem($ko);
            }
        }
        return $menuTree;
    }

    /**
     * 主菜单
     *
     * @return array
     */
    public function getMainMenu()
    {
        $nest = $this->getMenuNest();
        
        $list = [];
        foreach ($nest['tree'][0] as $vo) {
            $item = $this->parseMenuItem($vo);
            
            // 菜单链接
            if (empty($item['menu_url_origin'])) {
                $menuUrl = $this->getMainMenuUrl($item['menu_id']);
                is_null($menuUrl) || $item['menu_url'] = $menuUrl;
            }
            
            $list[] = $item;
        }
        return $list;
    }

    /**
     * 获取主菜单链接
     *
     * @param integer $menuId            
     * @return string
     */
    public function getMainMenuUrl($menuId)
    {
        $nest = $this->getMenuNest();
        
        // 二级菜单
        if (isset($nest['tree'][$menuId])) {
            $menu = $nest['tree'][$menuId][0];
            
            // 三级菜单
            if (isset($nest['tree'][$menu['id']])) {
                $menu = $nest['tree'][$menu['id']][0];
            }
            
            $item = $this->parseMenuItem($menu);
            return $item['menu_url'];
        }
        return null;
    }

    /**
     * 侧边菜单
     *
     * @return array
     */
    public function getSiderMenu()
    {
        $list = [];
        $currentMenu = $this->getCurrentMenu();
        
        if ($currentMenu) {
            $menuIds = $currentMenu['menu_ids'];
            $menuPid = end($menuIds);
            
            $menuNest = $this->getMenuNest();
            if (isset($menuNest['tree'][$menuPid])) {
                foreach ($menuNest['tree'][$menuPid] as $vo) {
                    $list[] = $this->parseMenuItem($vo);
                }
            }
        }
        return $list;
    }

    /**
     * 当前菜单
     *
     * @param string $menuFlag            
     * @return array
     */
    public function getCurrentMenu()
    {
        static $currentMenu;
        if (is_null($currentMenu)) {
            
            $menuFlag = $this->getCurrentMenuFlag();
            $currentMenu = $this->getMenuByFlag($menuFlag);
            
            // 菜单存在
            if ($currentMenu) {
                $menuPids = $this->getMenuPids($currentMenu['menu_pid']);
                $currentMenu['menu_ids'] = array_merge([
                    $currentMenu['id']
                ], $menuPids);
            }
        }
        return $currentMenu;
    }

    /**
     * 获取上级菜单IDS
     *
     * @param integer $menuPid            
     * @return array
     */
    public function getMenuPids($menuPid)
    {
        $nest = $this->getMenuNest();
        
        $menuPids = [
            $menuPid
        ];
        $menuList = $nest['list'];
        while (1) {
            // 无上级菜单
            if (! isset($menuList[$menuPid])) {
                break;
            }
            
            // 顶级菜单或者死循环
            $menu = $menuList[$menuPid];
            if ($menu['menu_pid'] == 0 || $menu['id'] == $menu['menu_pid']) {
                break;
            }
            
            $menuPid = $menuList[$menuPid]['menu_pid'];
            $menuPids[] = $menuPid;
        }
        return $menuPids;
    }

    /**
     * 根据标识获取菜单
     *
     * @param string $menuFlag            
     * @return array
     */
    public function getMenuByFlag($menuFlag)
    {
        $nest = $this->getMenuNest();
        foreach ($nest['list'] as $vo) {
            if ($vo['menu_flag'] == $menuFlag) {
                return $vo;
            }
        }
        return null;
    }

    /**
     * 获取当前菜单标识
     *
     * @return string
     */
    public function getCurrentMenuFlag()
    {
        if (defined('_MODULE_')) {
            $menuFlag = 'module' . '/' . _MODULE_ . '/' . _CONTROLLER_ . '/' . _ACTION_;
        } else {
            $menuFlag = Common::getSingleton()->getCurrentAction();
        }
        return $menuFlag;
    }

    /**
     * 解析菜单项
     *
     * @param array $menu            
     * @return array
     */
    protected function parseMenuItem($menu)
    {
        $curentMenu = $this->getCurrentMenu();
        return [
            'menu_id' => $menu['id'],
            'menu_name' => $menu['menu_name'],
            'menu_url_origin' => $menu['menu_url'],
            'menu_url' => $this->parseMenuUrl($menu['menu_url'], $menu['menu_build']),
            'menu_target' => $menu['menu_target'],
            'menu_active' => $curentMenu && in_array($menu['id'], $curentMenu['menu_ids']) ? 1 : 0
        ];
    }

    /**
     * 解析菜单链接
     *
     * @param string $menuUrl            
     * @param boolean $needBuild            
     * @return string
     */
    protected function parseMenuUrl($menuUrl, $needBuild = true)
    {
        return $needBuild ? Url::build($menuUrl) : $menuUrl;
    }
}