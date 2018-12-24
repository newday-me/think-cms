<?php

namespace app\manage\service;

use core\base\Service;
use core\db\manage\data\ManageMenuLinkData;
use core\db\manage\data\ManageUserGroupData;
use core\db\manage\validate\ManageUserGroupValidate;
use app\manage\logic\UserGroupLogic;

class UserGroupService extends Service
{

    /**
     * 获取群组
     *
     * @param string $groupNo
     * @return array|null
     */
    public function getGroup($groupNo)
    {
        $this->resetError();

        $group = ManageUserGroupData::getSingleton()->getGroup($groupNo);
        if ($group) {
            return [
                'group_no' => $group['group_no'],
                'group_name' => $group['group_name'],
                'group_info' => $group['group_info'],
            ];
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '群组不存在');
            return null;
        }
    }

    /**
     * 获取群组树
     *
     * @return array
     */
    public function getGroupTree()
    {
        return UserGroupLogic::getSingleton()->getGroupTree();
    }

    /**
     * 获取群组的菜单树
     *
     * @param string $groupNo
     * @return array|null
     */
    public function getGroupMenuTree($groupNo)
    {
        $this->resetError();

        $menuTree = UserGroupLogic::getSingleton()->getGroupMenuTree($groupNo);
        if ($menuTree) {
            return $menuTree;
        } else {
            $this->setErrorByObject(UserGroupLogic::getSingleton());
            return null;
        }
    }

    /**
     * 保存群组权限
     *
     * @param string $groupNo
     * @param array $menuNos
     */
    public function saveGroupAuth($groupNo, $menuNos)
    {
        ManageMenuLinkData::getSingleton()->saveGroupMenuNos($groupNo, $menuNos);
    }

    /**
     * 创建群组
     *
     * @param array $data
     * @return bool|null
     */
    public function createGroup($data)
    {
        $this->resetError();

        $validate = ManageUserGroupValidate::getSingleton();
        if (!$validate->scene('add')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        $group = ManageUserGroupData::getSingleton()->createGroup($data);
        if ($group) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '创建群组失败');
            return null;
        }
    }

    /**
     * 保存群组
     *
     * @param string $groupNo
     * @param array $data
     * @return bool|null
     */
    public function updateGroup($groupNo, $data)
    {
        $this->resetError();

        $validate = ManageUserGroupValidate::getSingleton();
        if (!$validate->scene('edit')->check($data)) {
            $this->setError(self::ERROR_CODE_DEFAULT, $validate->getError());
            return null;
        }

        if (ManageUserGroupData::getSingleton()->updateGroup($groupNo, $data)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '更新群组失败');
            return null;
        }
    }

    /**
     * 拖动群组
     *
     * @param string $mode
     * @param string $fromGroupNo
     * @param string $toGroupNo
     * @return bool|null
     */
    public function dragGroup($mode, $fromGroupNo, $toGroupNo)
    {
        $this->resetError();

        $result = UserGroupLogic::getSingleton()->drag($mode, $fromGroupNo, $toGroupNo);
        if ($result) {
            return $result;
        } else {
            $this->setErrorByObject(UserGroupLogic::getSingleton());
            return null;
        }
    }

    /**
     * 删除群组
     *
     * @param string $groupNo
     * @return bool|null
     */
    public function deleteGroup($groupNo)
    {
        $this->resetError();

        $groupCount = ManageUserGroupData::getSingleton()->getSubGroupCount($groupNo);
        if ($groupCount) {
            $this->setError(self::ERROR_CODE_DEFAULT, '请先删除该群组下的子群组');
            return null;
        }

        if (ManageUserGroupData::getSingleton()->deleteMenu($groupNo)) {
            return true;
        } else {
            $this->setError(self::ERROR_CODE_DEFAULT, '删除群组失败');
            return null;
        }
    }

}