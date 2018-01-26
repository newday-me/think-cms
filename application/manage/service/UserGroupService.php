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
     * @return \cms\core\objects\ReturnObject
     */
    public function getGroup($groupNo)
    {
        $group = ManageUserGroupData::getSingleton()->getGroup($groupNo);
        if ($group) {
            return $this->returnSuccess('获取成功', [
                'group_no' => $group['group_no'],
                'group_name' => $group['group_name'],
                'group_info' => $group['group_info'],
            ]);
        } else {
            return $this->returnError('群组不存在');
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
     * @return \cms\core\objects\ReturnObject
     */
    public function getGroupMenuTree($groupNo)
    {
        return UserGroupLogic::getSingleton()->getGroupMenuTree($groupNo);
    }

    /**
     * 保存群组权限
     *
     * @param string $groupNo
     * @param array $menuNos
     * @return \cms\core\objects\ReturnObject
     */
    public function saveGroupAuth($groupNo, $menuNos)
    {
        ManageMenuLinkData::getSingleton()->saveGroupMenuNos($groupNo, $menuNos);
        return $this->returnSuccess('操作成功');
    }

    /**
     * 创建群组
     *
     * @param array $data
     * @return \cms\core\objects\ReturnObject
     */
    public function createGroup($data)
    {
        $validate = ManageUserGroupValidate::getSingleton();
        if (!$validate->scene('add')->check($data)) {
            return $this->returnError($validate->getError());
        }

        $group = ManageUserGroupData::getSingleton()->createGroup($data);
        if ($group) {
            return $this->returnSuccess('创建成功');
        } else {
            return $this->returnError('创建失败');
        }
    }

    /**
     * 保存群组
     *
     * @param string $groupNo
     * @param array $data
     * @return \cms\core\objects\ReturnObject
     */
    public function updateGroup($groupNo, $data)
    {
        $validate = ManageUserGroupValidate::getSingleton();
        if (!$validate->scene('edit')->check($data)) {
            return $this->returnError($validate->getError());
        }

        if (ManageUserGroupData::getSingleton()->updateGroup($groupNo, $data)) {
            return $this->returnSuccess('保存成功');
        } else {
            return $this->returnError('保存失败');
        }
    }

    /**
     * 拖动群组
     *
     * @param string $mode
     * @param string $fromGroupNo
     * @param string $toGroupNo
     * @return \cms\core\objects\ReturnObject
     */
    public function dragGroup($mode, $fromGroupNo, $toGroupNo)
    {
        return UserGroupLogic::getSingleton()->drag($mode, $fromGroupNo, $toGroupNo);
    }

    /**
     * 删除群组
     *
     * @param string $groupNo
     * @return \cms\core\objects\ReturnObject
     */
    public function deleteGroup($groupNo)
    {
        $groupCount = ManageUserGroupData::getSingleton()->getSubGroupCount($groupNo);
        if ($groupCount) {
            return $this->returnError('请先删除该群组下的子群组');
        }

        if (ManageUserGroupData::getSingleton()->deleteMenu($groupNo)) {
            return $this->returnSuccess('删除成功');
        } else {
            return $this->returnError('删除失败');
        }
    }

}