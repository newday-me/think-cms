<?php
namespace core\manage\logic;

use think\Url;
use cms\Common;
use core\Logic;
use core\manage\model\MenuModel;
use core\manage\model\UserModel;

class MenuLogic extends Logic
{

    /**
     * 添加菜单
     *
     * @param array $data            
     * @return boolean
     */
    public function addMenu($data)
    {
        $data = $this->processMenuData($data);
        $model = MenuModel::getSingleton();
        return $model->save($data);
    }

    /**
     * 修改菜单
     *
     * @param array $data            
     * @param array $map            
     * @return boolean
     */
    public function saveMenu($data, $map)
    {
        $data = $this->processMenuData($data);
        $model = MenuModel::getSingleton();
        return $model->save($data, $map);
    }

    /**
     * 处理菜单数据
     *
     * @param array $data            
     * @return array
     */
    public function processMenuData($data)
    {
        $data['menu_flag'] = $this->parseMenuFlag($data['menu_url'], $data['menu_build']);
        return $data;
    }

    /**
     * 解析菜单标识
     *
     * @param string $link            
     * @param boolean $build            
     * @return string
     */
    public function parseMenuFlag($link, $build = true)
    {
        // 外链
        if ($build == false) {
            return md5($link);
        }
        
        // 测试连接
        $url_test = 'path/test/domain';
        $url_path = str_replace($url_test . '.html', '', Url::build($url_test, '', true, true));
        
        // 相对url
        $url = Url::build($link, '', true, true);
        $url_relative = str_replace([
            $url_path,
            '.html'
        ], '', $url);
        
        // Url标识
        $arr = explode('/', $url_relative);
        if (strpos($link, '@module') !== false) {
            $arr = array_slice($arr, 0, 4);
        } else {
            $arr = array_slice($arr, 0, 3);
        }
        return implode('/', $arr);
    }

    /**
     * 获取菜单列表
     *
     * @return array
     */
    public function getMenuList()
    {
        $menuTree = $this->getMenuTree();
        
        $menus = [];
        $menus[] = [
            'name' => '无',
            'value' => 0
        ];
        foreach ($menuTree['main_menu'] as $vo) {
            $menus[] = [
                'name' => $vo['menu_name'],
                'value' => $vo['menu_id']
            ];
            foreach ($menuTree['sub_menu'][$vo['menu_id']] as $ko) {
                $menus[] = [
                    'name' => '--' . $ko['menu_name'],
                    'value' => $ko['menu_id']
                ];
            }
        }
        return $menus;
    }

    /**
     * 菜单树
     *
     * @return array
     */
    public function getMenuTree()
    {
        $menu = [
            'main_menu' => [],
            'sub_menu' => [],
            'sub_sub_menu' => []
        ];
        $model = MenuModel::getSingleton();
        
        // 一级菜单
        $map = [
            'menu_pid' => 0
        ];
        $list = $model->where($map)
            ->order('menu_sort asc')
            ->select();
        $mainPids = [];
        foreach ($list as $vo) {
            $mainPids[] = $vo['id'];
            $menu['main_menu'][$vo['id']] = [
                'menu_id' => $vo['id'],
                'menu_name' => $vo['menu_name']
            ];
            $menu['sub_menu'][$vo['id']] = [];
        }
        
        // 二级菜单
        $map = [
            'menu_pid' => [
                'in',
                $mainPids
            ]
        ];
        $list = $model->where($map)
            ->order('menu_sort asc')
            ->select();
        $subPids = [];
        foreach ($list as $vo) {
            $subPids[] = $vo['id'];
            $menu['sub_menu'][$vo['menu_pid']][$vo['id']] = [
                'menu_id' => $vo['id'],
                'menu_name' => $vo['menu_name']
            ];
            $menu['sub_sub_menu'][$vo['id']] = [];
        }
        
        // 三级菜单
        $map = [
            'menu_pid' => [
                'in',
                $subPids
            ]
        ];
        $list = $model->where($map)
            ->order('menu_sort asc')
            ->select();
        foreach ($list as $vo) {
            $menu['sub_sub_menu'][$vo['menu_pid']][$vo['id']] = [
                'menu_id' => $vo['id'],
                'menu_name' => $vo['menu_name']
            ];
        }
        
        return $menu;
    }

    /**
     * 主菜单
     *
     * @param number $userId            
     * @return array
     */
    public function getMainMenu($userId)
    {
        $currentMenu = $this->getCurrentMenu();
        $menus = $this->getUserMenu($userId);
        foreach ($menus as &$menu) {
            // 菜单链接
            if (empty($menu['menu_url_origin'])) {
                $menu_url = $this->getMainMenuUrl($menu['menu_id'], $userId);
                is_null($menu_url) || $menu['menu_url'] = $menu_url;
            }
            
            // 选中状态
            if ($currentMenu && $menu['menu_id'] == $currentMenu['menu_pid']) {
                $menu['menu_active'] = 1;
            } else {
                $menu['menu_active'] = 0;
            }
        }
        unset($menu);
        return $menus;
    }

    /**
     * 主菜单链接
     *
     * @param integer $menuId            
     * @param integer $userId            
     * @return string
     */
    public function getMainMenuUrl($menuId, $userId)
    {
        // 用户菜单
        $menuIds = $this->getUserMenuIds($userId);
        if (! in_array($menuId, $menuIds)) {
            return '';
        }
        
        $map = array(
            'menu_status' => 1,
            'menu_pid' => $menuId,
            'id' => [
                'in',
                $menuIds
            ]
        );
        $menu = MenuModel::getSingleton()->where($map)
            ->order('menu_sort asc')
            ->find();
        return $menu ? $this->parseMenuUrl($menu['menu_url'], $menu['menu_build']) : '';
    }

    /**
     * 侧边菜单
     *
     * @param number $userId            
     * @return array
     */
    public function getSiderMenu($userId)
    {
        // 当前菜单
        $currentMenu = $this->getCurrentMenu();
        if (empty($currentMenu)) {
            return [];
        }
        
        $menus = $this->getUserMenu($userId, $currentMenu['menu_pid']);
        foreach ($menus as &$menu) {
            if (isset($menu['sub_menu'])) {
                $menu['menu_active'] = 0;
                
                // 遍历子菜单，判断选中状态
                foreach ($menu['sub_menu'] as &$item) {
                    if ($item['menu_id'] == $currentMenu['id']) {
                        $item['menu_active'] = 1;
                        $menu['menu_active'] = 1;
                    } else {
                        $item['menu_active'] = 0;
                    }
                }
                unset($item);
            } else {
                // 选中状态
                if ($menu['menu_id'] == $currentMenu['id']) {
                    $menu['menu_active'] = 1;
                } else {
                    $menu['menu_active'] = 0;
                }
            }
        }
        unset($menu);
        return $menus;
    }

    /**
     * 获取用户菜单
     *
     * @param number $uid            
     * @param number $pid            
     * @return array
     */
    public function getUserMenu($userId, $menuPid = 0)
    {
        $menuIds = $this->getUserMenuIds($userId);
        $map = [
            'menu_status' => 1,
            'menu_pid' => $menuPid,
            'id' => [
                'in',
                $menuIds
            ]
        ];
        $model = MenuModel::getSingleton();
        $list = $model->where($map)
            ->order('menu_sort asc')
            ->select();
        
        $menu = [];
        foreach ($list as $v) {
            if ($v['menu_group'] && $menuPid > 0) {
                $key = 'group_' . md5($v['menu_group']);
                if (! isset($menu[$key])) {
                    $menu[$key] = [
                        'menu_name' => $v['menu_group'],
                        'sub_menu' => []
                    ];
                }
                $menu[$key]['sub_menu'][] = $this->parseMenuItem($v);
            } else {
                $key = 'menu_' . $v['id'];
                $menu[$key] = $this->parseMenuItem($v);
            }
        }
        
        return $menu;
    }

    /**
     * 用户菜单
     *
     * @param number $userId            
     * @return array
     */
    public function getUserMenuIds($userId)
    {
        $member = UserModel::getSingleton()->get($userId);
        if (empty($member)) {
            return [];
        }
        
        return UserGroupLogic::getSingleton()->getGroupMenuIds($member['group_id']);
    }

    /**
     * 当前菜单
     *
     * @param string $menuFlag            
     * @return array
     */
    public function getCurrentMenu($menuFlag = null)
    {
        // 当前菜单
        $currentMenu = $this->getMenuByFlag($menuFlag);
        if (empty($currentMenu)) {
            return null;
        }
        
        // 上级菜单
        $parentMenu = MenuModel::getSingleton()->get($currentMenu['menu_pid']);
        if ($parentMenu['menu_pid'] > 0) {
            return $this->getCurrentMenu($parentMenu['menu_flag']);
        } else {
            return $currentMenu;
        }
    }

    /**
     * 根据标识获取菜单
     *
     * @param string $menuFlag            
     * @return array
     */
    public function getMenuByFlag($menuFlag = null)
    {
        $menuFlag || $menuFlag = $this->getCurrentMenuFlag();
        $map = [
            'menu_flag' => $menuFlag,
            'menu_pid' => [
                'gt',
                0
            ]
        ];
        return MenuModel::getSingleton()->where($map)->find();
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
        return [
            'menu_id' => $menu['id'],
            'menu_name' => $menu['menu_name'],
            'menu_url_origin' => $menu['menu_url'],
            'menu_url' => $this->parseMenuUrl($menu['menu_url'], $menu['menu_build']),
            'menu_target' => $menu['menu_target']
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