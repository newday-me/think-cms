<?php
namespace core\manage\logic;

use cms\Common;
use core\Logic;
use core\manage\model\UserModel;

class UserLogic extends Logic
{

    /**
     * 获取状态下拉
     *
     * @return array
     */
    public function getSelectStatus()
    {
        return [
            [
                'name' => '启用',
                'value' => 1
            ],
            [
                'name' => '禁用',
                'value' => 0
            ]
        ];
    }

    public function getSelectList()
    {
        $list = UserModel::getInstance()->select();
        $users = [];
        foreach ($list as $vo) {
            $users[$vo['id']] = [
                'name' => $vo['user_name'] . '(' . $vo['user_nick'] . ')',
                'value' => $vo['id']
            ];
        }
        return $users;
    }

    public function getUserMenuIds($userId)
    {
        $user = UserModel::get($userId);
        $menuIds = [];
        foreach ($user->groups as $group) {
            $menuIds = array_merge($menuIds, explode(',', $group['group_menus']));
        }
        return array_unique(array_filter($menuIds));
    }

    /**
     * 是否超级管理员
     *
     * @param integer $userId            
     * @return boolean
     */
    public function isSuperAdmin($userId)
    {
        return $userId == 1;
    }

    /**
     * 添加用户
     *
     * @param array $data            
     * @param mixed $groupIds            
     * @return void
     */
    public function addUser($data, $groupIds)
    {
        $data = $this->processPasswdData($data);
        
        // 创建用户
        $user = UserModel::getInstance()->create($data);
        
        // 关联群组
        $this->attachUserGroups($user['id'], $groupIds);
    }

    /**
     * 更新用户
     *
     * @param integer $userId            
     * @param array $data            
     * @param mixed $groupIds            
     * @return void
     */
    public function saveUser($userId, $data, $groupIds)
    {
        $data = $this->processPasswdData($data);
        
        // 更新用户
        $map = [
            'id' => $userId
        ];
        UserModel::getInstance()->update($data, $map);
        
        // 关联群组
        $this->attachUserGroups($userId, $groupIds);
    }

    /**
     * 关联用户群组
     *
     * @param integer $userId            
     * @param array $groupIds            
     * @return \think\model\Pivot
     */
    public function attachUserGroups($userId, $groupIds)
    {
        is_array($groupIds) || $groupIds = array_filter(explode(',', $groupIds));
        
        // 保存关联
        $user = UserModel::get($userId);
        $user->groups()->detach();
        return $user->groups()->attach($groupIds);
    }

    /**
     * 处理密码数据
     *
     * @param array $data            
     * @return array
     */
    public function processPasswdData($data)
    {
        // 加密密码
        if (isset($data['user_passwd'])) {
            if (empty($data['user_passwd'])) {
                unset($data['user_passwd']);
            } else {
                $data['user_passwd'] = $this->encryptPasswd($data['user_passwd']);
            }
        }
        
        // 移除重复密码
        unset($data['user_passwd_confirm']);
        
        return $data;
    }

    /**
     * 验证登录
     *
     * @param string $name            
     * @param string $passwd            
     * @return array($code, $msg, $user)
     */
    public function checkLogin($name, $passwd)
    {
        $map = [
            'user_name' => $name,
            'user_passwd' => $this->encryptPasswd($passwd)
        ];
        $user = UserModel::getInstance()->where($map)->find();
        
        // 用户状态
        if (empty($user)) {
            return [
                - 1,
                '账号或者密码错误',
                null,
                null
            ];
        } elseif ($user['user_status'] == 0) {
            return [
                - 2,
                '未启用的账号',
                null,
                null
            ];
        } elseif ($user['user_status'] == - 1) {
            return [
                - 3,
                '该账号已经被禁用',
                null,
                null
            ];
        }
        
        // 登录次数
        $this->addLoginRecord($user['id']);
        
        return [
            1,
            '登录成功',
            $user
        ];
    }

    /**
     * 增加登录记录
     *
     * @param integer $userId            
     * @return boolean
     */
    public function addLoginRecord($userId)
    {
        // 登录日志
        UserLoginLogic::getSingleton()->addLogin($userId);
        
        $model = UserModel::getInstance();
        $common = Common::getSingleton();
        $map = [
            'id' => $userId
        ];
        $data = [
            'login_count' => [
                'exp',
                'login_count + 1'
            ],
            'login_time' => time(),
            'login_ip' => $common->getIp()
        ];
        return $model->save($data, $map);
    }

    /**
     * 加密密码
     *
     * @param string $passwd            
     * @return string
     */
    public function encryptPasswd($passwd)
    {
        return md5(gzcompress($passwd) . base64_decode($passwd));
    }
}