<?php
namespace app\manage\controller;

use think\Request;
use core\manage\logic\UserLogic;
use core\manage\model\UserModel;
use core\manage\validate\UserValidate;
use core\manage\logic\UserGroupLogic;

class User extends Base
{

    /**
     * 用户列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '用户管理';
        
        // 群组列表
        $this->assignGroupList();
        
        // 状态列表
        $this->assignStatusList();
        
        // 分页列表
        $list = UserModel::getSingleton()->select();
        $this->_list($list);
        
        return $this->fetch();
    }

    /**
     * 添加用户
     *
     * @param Request $request            
     * @return string|void
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'user_name' => $request->param('user_name'),
                'user_nick' => $request->param('user_nick'),
                'user_passwd' => $request->param('user_passwd'),
                'user_passwd_confirm' => $request->param('user_passwd_confirm'),
                'group_id' => $request->param('group_id'),
                'user_status' => $request->param('user_status', 0)
            ];
            
            // 验证
            $this->_validate(UserValidate::class, $data, 'add');
            
            // 密码
            $logic = UserLogic::getSingleton();
            $data = $logic->processPasswdData($data);
            
            // 添加
            $this->_add(UserModel::class, $data);
        } else {
            $this->siteTitle = '新增用户';
            
            // 群组列表
            $this->assignGroupList();
            
            // 状态列表
            $this->assignStatusList();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑用户
     *
     * @param Request $request            
     * @return string|void
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'user_name' => $request->param('user_name'),
                'user_nick' => $request->param('user_nick'),
                'user_passwd' => $request->param('user_passwd'),
                'user_passwd_confirm' => $request->param('user_passwd_confirm'),
                'group_id' => $request->param('group_id'),
                'user_status' => $request->param('user_status', 0)
            ];
            
            // 验证
            $scene = empty($data['user_passwd']) ? 'edit_info' : 'edit_passwd';
            $this->_validate(UserValidate::class, $data, $scene);
            
            // 密码
            $logic = UserLogic::getSingleton();
            $data = $logic->processPasswdData($data);
            
            // 修改
            $this->_edit(UserModel::class, $data);
        } else {
            $this->siteTitle = '编辑用户';
            
            // 用户
            $this->_record(UserModel::class);
            
            // 群组列表
            $this->assignGroupList();
            
            // 状态列表
            $this->assignStatusList();
            
            return $this->fetch();
        }
    }

    /**
     * 更改用户
     *
     * @return void
     */
    public function modify()
    {
        $fields = [
            'group_id',
            'user_status'
        ];
        $this->_modify(UserModel::class, $fields);
    }

    /**
     * 删除用户
     *
     * @return void
     */
    public function delete()
    {
        $this->_delete(UserModel::class, false);
    }

    /**
     * 赋值群组列表
     *
     * @return void
     */
    protected function assignGroupList()
    {
        $logic = UserGroupLogic::getSingleton();
        $groupList = $logic->getGroupList();
        $this->assign('group_list', $groupList);
    }

    /**
     * 赋值状态列表
     *
     * @return void
     */
    protected function assignStatusList()
    {
        $model = UserModel::getSingleton();
        $statusList = $model->getStatusList();
        $this->assign('status_list', $statusList);
    }
}