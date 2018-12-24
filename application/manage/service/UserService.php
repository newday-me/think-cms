<?php

namespace app\manage\service;

use think\db\Query;
use think\facade\Url;
use core\base\Service;
use core\db\manage\data\ManageUserData;
use core\db\manage\constant\ManageUserConstant;
use core\db\manage\data\ManageUserGroupLinkData;
use core\db\manage\validate\ManageUserValidate;
use app\manage\logic\WidgetLogic;
use app\manage\logic\UserLogic;
use app\manage\logic\PageLogic;
use app\manage\logic\LoginLogic;

class UserService extends Service
{

    /**
     * 获取用户
     *
     * @param string $userNo
     * @return array|null
     */
    public function getUser($userNo)
    {
        $this->resetError();

        $user = ManageUserData::getSingleton()->getUser($userNo);
        if (empty($user)) {
            $this->setError(self::ERROR_CODE_DEFAULT, '用户不存在');
            return null;
        } else {
            return [
                'user_no' => $user['user_no'],
                'user_name' => $user['user_name'],
                'user_nick' => $user['user_nick'],
                'user_status' => $user['user_status']
            ];
        }
    }

    /**
     * 获取用户群组树
     *
     * @param string $userNo
     * @return array
     */
    public function getUserGroupTree($userNo)
    {
        return UserLogic::getSingleton()->getUserGroupTree($userNo);
    }

    /**
     * 创建用户
     *
     * @param array $data
     * @return bool|null
     */
    public function createUser($data)
    {
        $this->resetError();

        $validate = ManageUserValidate::getSingleton();
        if (!$validate->scene('add')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        // 加密密码
        $data['user_password'] = ManageUserData::getSingleton()->encryptPassword($data['user_password']);

        $user = ManageUserData::getSingleton()->createUser($data);
        if ($user) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '创建用户失败');
            return null;
        }
    }

    /**
     * 更新用户
     *
     * @param string $userNo
     * @param array $data
     * @return bool|null
     */
    public function updateUser($userNo, $data)
    {
        $this->resetError();

        $validate = ManageUserValidate::getSingleton();
        if (!$validate->scene('edit')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        // 加密密码
        if ($data['user_password']) {
            $data['user_password'] = ManageUserData::getSingleton()->encryptPassword($data['user_password']);
        } else {
            unset($data['user_password']);
        }

        if (ManageUserData::getSingleton()->updateUser($userNo, $data)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '保存用户失败');
            return null;
        }
    }

    /**
     * 更新账号
     *
     * @param array $data
     * @return bool|null
     */
    public function updateAccount($data)
    {
        $this->resetError();

        $validate = ManageUserValidate::getSingleton();
        if (!$validate->scene('account')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        // 加密密码
        if ($data['user_password']) {
            $data['user_password'] = ManageUserData::getSingleton()->encryptPassword($data['user_password']);
        } else {
            unset($data['user_password']);
        }

        $userNo = LoginLogic::getSingleton()->getLoginUserNo();
        if (ManageUserData::getSingleton()->updateUser($userNo, $data)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '保存用户失败');
            return null;
        }
    }

    /**
     * 保存用户权限
     *
     * @param string $userNo
     * @param array $groupNos
     * @return int
     */
    public function saveUserAuth($userNo, $groupNos)
    {
        return ManageUserGroupLinkData::getSingleton()->saveUserGroupNos($userNo, $groupNos);
    }

    /**
     * 获取分页列表
     *
     * @param int $userStatus
     * @param string $keyword
     * @param int $nowPage
     * @param int $pageSize
     * @return array($list, $page)
     */
    public function getUserListPage($userStatus, $keyword, $nowPage, $pageSize = 10)
    {
        $closure = function (Query $query) use ($userStatus, $keyword) {
            if (!is_null($userStatus)) {
                $query->where('user_status', '=', $userStatus);
            }
            if ($keyword) {
                $query->where('user_name|user_nick', 'like', '%' . $keyword . '%');
            }
        };

        $list = ManageUserData::getSingleton()->getUserListPage($closure, $nowPage, $pageSize);
        $widget = WidgetLogic::getSingleton()->getWidget();
        $modifyUrl = Url::build('modify');
        foreach ($list as &$vo) {
            $vo['is_super'] = ManageUserData::getSingleton()->isSuperUser($vo);

            $groupNames = [];
            foreach ($vo['groups'] as $group) {
                $groupNames[] = $group['group_name'];
            }
            if (empty($groupNames)) {
                $vo['group_name_str'] = '待选择';
            } else {
                $vo['group_name_str'] = implode(',', $groupNames);
            }

            $vo['user_status_html'] = $widget->table('switch', [
                'value' => $vo['user_status'],
                'on' => ManageUserConstant::STATUS_ENABLE,
                'off' => ManageUserConstant::STATUS_DISABLE,
                'field' => 'user_status',
                'url' => $modifyUrl,
                'data_no' => $vo['user_no']
            ]);

            $vo['login_time_str'] = date('Y-m-d H:i:s', $vo['login_time']);
        }
        unset($vo);

        $total = ManageUserData::getInstance()->getUserCount($closure);
        $page = PageLogic::getSingleton()->buildPage($total, $nowPage, $pageSize);

        return [$list, $page->render()];
    }

    /**
     * 更改用户
     *
     * @param string $userNo
     * @param string $field
     * @param string $value
     * @return bool|null
     */
    public function modifyUser($userNo, $field, $value)
    {
        $this->resetError();

        $allowField = [
            'user_status'
        ];
        if (!in_array($field, $allowField)) {
            $this->setError(self::ERROR_CODE_DEFAULT, '非法的字段名');
            return null;
        }

        $data = [
            $field => $value
        ];
        if (ManageUserData::getSingleton()->updateUser($userNo, $data)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '保存用户失败');
            return null;
        }
    }

    /**
     * 删除用户
     *
     * @param string $userNo
     * @return bool|null
     */
    public function deleteUser($userNo)
    {
        $this->resetError();

        $user = ManageUserData::getSingleton()->getUser($userNo);
        if (empty($user)) {
            $this->setError(self::ERROR_CODE_DEFAULT, '用户不存在');
            return null;
        }

        if (ManageUserData::getSingleton()->isSuperUser($user)) {
            $this->setError(self::ERROR_CODE_DEFAULT, '超级管理员不能删除');
            return null;
        }

        if (ManageUserData::getSingleton()->deleteUser($userNo)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '删除用户失败');
            return null;
        }
    }

}