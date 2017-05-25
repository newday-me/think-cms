<?php
namespace app\manage\controller;

use think\Request;
use core\manage\logic\MenuLogic;
use core\manage\model\UserModel;
use core\manage\logic\UserGroupLogic;
use core\manage\model\UserGroupModel;
use core\manage\validate\UserGroupValidate;

class UserGroup extends Base
{

    /**
     * 群组列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '用户群组';
        
        // 记录列表
        $list = UserGroupModel::getInstance()->select();
        $this->_list($list);
        
        // 群组状态下拉
        $this->assignSelectGroupStatus();
        
        return $this->fetch();
    }

    /**
     * 添加群组
     *
     * @param Request $request            
     * @return mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'group_name' => $request->param('group_name'),
                'group_info' => $request->param('group_info', ''),
                'home_page' => $request->param('home_page', ''),
                'group_status' => $request->param('group_status', 0)
            ];
            
            // 验证
            $this->_validate(UserGroupValidate::class, $data, 'add');
            
            // 添加
            $this->_add(UserGroupModel::class, $data);
        } else {
            $this->siteTitle = '新增群组';
            
            // 群组状态下拉
            $this->assignSelectGroupStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑群组
     *
     * @param Request $request            
     * @return mixed
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'group_name' => $request->param('group_name'),
                'group_info' => $request->param('group_info', ''),
                'home_page' => $request->param('home_page', ''),
                'group_status' => $request->param('group_status', 0)
            ];
            
            // 验证
            $this->_validate(UserGroupValidate::class, $data, 'edit');
            
            // 保存
            $this->_edit(UserGroupModel::class, $data);
        } else {
            $this->siteTitle = '编辑群组';
            
            // 记录
            $this->_record(UserGroupModel::class);
            
            // 群组状态下拉
            $this->assignSelectGroupStatus();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑权限
     *
     * @param Request $request            
     * @return mixed
     */
    public function auth(Request $request)
    {
        $gid = $request->param('gid');
        if (empty($gid)) {
            $this->error('群组ID为空');
        }
        
        if ($request->isPost()) {
            $groupMenus = $request->param('group_menus/a');
            if (empty($groupMenus) || count($groupMenus) == 0) {
                $this->error('权限菜单为空');
            }
            
            // 保存
            $map = [
                'id' => $gid
            ];
            $data = [
                'group_menus' => implode(',', $groupMenus)
            ];
            $this->_edit(UserGroupModel::class, $data, $map);
        } else {
            $this->siteTitle = '编辑权限';
            $this->assign('gid', $gid);
            
            // 群组菜单IDS
            $this->assignMenuIds($gid);
            
            // 菜单列表
            $this->assignMenuList();
            
            return $this->fetch();
        }
    }

    /**
     * 更改群组
     *
     * @return void
     */
    public function modify()
    {
        $fields = [
            'group_status'
        ];
        $this->_modify(UserGroupModel::class, $fields);
    }

    /**
     * 删除群组
     *
     * @return void
     */
    public function delete()
    {
        $groupId = $this->_id();
        $map = [
            'user_gid' => $groupId
        ];
        if (UserModel::getInstance()->where($map)->find()) {
            $this->error('请先删除该群组下的账号');
        }
        
        $this->_delete(UserGroupModel::class, false);
    }

    /**
     * 赋值状态列表
     */
    protected function assignSelectGroupStatus()
    {
        $selectGroupStatus = UserGroupLogic::getSingleton()->getSelectStatus();
        $this->assign('select_group_status', $selectGroupStatus);
    }

    /**
     * 赋值菜单列表
     */
    protected function assignMenuList()
    {
        $logic = MenuLogic::getSingleton();
        $menuNest = $logic->getMenuNest();
        $this->assign('menu_list', $menuNest['tree']);
    }

    /**
     * 赋值菜单IDS
     *
     * @param integer $gid            
     */
    protected function assignMenuIds($gid)
    {
        $logic = UserGroupLogic::getSingleton();
        $menuIds = $logic->getGroupMenuIds($gid);
        $this->assign('menu_ids', $menuIds);
    }
}