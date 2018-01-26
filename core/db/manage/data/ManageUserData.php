<?php

namespace core\db\manage\data;

use think\facade\Request;
use core\base\Data;
use core\db\manage\constant\ManageMenuConstant;
use core\db\manage\model\ManageUserModel;
use core\db\manage\view\ManageUserView;

class ManageUserData extends Data
{

    /**
     * 创建用户
     *
     * @param array $data
     * @return array|null
     */
    public function createUser($data)
    {
        $record = ManageUserModel::create($data);
        return $this->recordToArray($record);
    }

    /**
     * 是否超级用户
     *
     * @param array $user
     * @return bool
     */
    public function isSuperUser($user)
    {
        return $user['id'] == 1;
    }

    /**
     * 获取用户
     *
     * @param string $userNo
     * @return array|null
     */
    public function getUser($userNo)
    {
        $map = [
            'user_no' => $userNo
        ];
        $record = ManageUserModel::get($map);
        return $this->recordToArray($record);
    }

    /**
     * 根据用户名获取用户
     *
     * @param string $userName
     * @return array|null
     */
    public function getUserByUserName($userName)
    {
        $map = [
            'user_name' => $userName
        ];
        $record = ManageUserModel::get($map);
        return $this->recordToArray($record);
    }

    /**
     * 获取用户列表
     *
     * @param \Closure $closure
     * @param int $page
     * @param int $pageSize
     * @return array|null
     */
    public function getUserListPage($closure, $page, $pageSize)
    {
        $list = ManageUserModel::getInstance()->with('groups')->where($closure)->page($page, $pageSize)->select();
        return $this->listToArray($list);
    }

    /**
     * 获取用户记录数
     *
     * @param \Closure $closure
     * @return int|string
     */
    public function getUserCount($closure)
    {
        return ManageUserModel::getInstance()->where($closure)->count();
    }

    /**
     * 验证登录
     *
     * @param string $userName
     * @param string $userPassword
     * @return array|null
     */
    public function checkLogin($userName, $userPassword)
    {
        $manageUser = $this->getUserByUserName($userName);
        if (empty($manageUser)) {
            return null;
        } elseif ($manageUser['user_password'] != $this->encryptPassword($userPassword)) {
            return null;
        }
        return $manageUser;
    }

    /**
     * 更新用户
     *
     * @param string $userNo
     * @param array $data
     * @return false|int
     */
    public function updateUser($userNo, $data)
    {
        $map = [
            'user_no' => $userNo
        ];
        return ManageUserModel::getInstance()->save($data, $map);
    }

    /**
     * 更新最近登录
     *
     * @param string $userNo
     * @return false|int
     */
    public function updateLogin($userNo)
    {
        $data = [
            'login_time' => time(),
            'login_ip' => Request::ip(),
            'login_count' => [
                'exp',
                'login_count+1'
            ]
        ];
        return $this->updateUser($userNo, $data);
    }

    /**
     * 删除用户
     *
     * @param string $userNo
     * @return int
     */
    public function deleteUser($userNo)
    {
        $map = [
            'user_no' => $userNo
        ];
        return ManageUserModel::getInstance()->where($map)->delete();
    }

    /**
     * 获取用户菜单
     *
     * @param string $userNo
     * @param string $menuAction
     * @return array|null
     */
    public function getUserMenu($userNo, $menuAction)
    {
        $map = [
            'a.user_no' => $userNo,
            'e.menu_action' => $menuAction,
            'e.menu_status' => ManageMenuConstant::STATUS_ENABLE
        ];
        $field = [
            'e.*'
        ];
        $record = ManageUserView::getSingleton()->menuQuery()->where($map)->field($field)->find();
        return $this->recordToArray($record);
    }

    /**
     * 获取用户菜单
     *
     * @param string $userNo
     * @return array
     */
    public function getUserMenuList($userNo)
    {
        $map = [
            'a.user_no' => $userNo,
            'e.menu_type' => ManageMenuConstant::TYPE_MENU,
            'e.menu_status' => ManageMenuConstant::STATUS_ENABLE
        ];
        $field = [
            'e.*'
        ];
        $list = ManageUserView::getSingleton()->menuQuery()->where($map)->field($field)->group('e.menu_no')->order('e.menu_sort asc, e.id asc')->select();
        return $this->listToArray($list);
    }

    /**
     * 加密密码
     *
     * @param string $password
     * @return string
     */
    public function encryptPassword($password)
    {
        $passwordHash = md5($password);
        return md5(gzcompress($passwordHash) . base64_decode($passwordHash));
    }

}