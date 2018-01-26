<?php

namespace app\manage\logic;

use think\facade\Url;
use core\logic\support\TreeLogic;
use core\logic\support\DragLogic;
use core\db\manage\data\ManageMenuLinkData;
use core\db\manage\data\ManageUserGroupData;
use core\db\manage\constant\ManageUserGroupConstant;

class UserGroupLogic extends DragLogic
{

    /**
     * 获取群组树
     *
     * @return array
     */
    public function getGroupTree()
    {
        $groupList = ManageUserGroupData::getSingleton()->getGroupList();
        $groupTree = TreeLogic::getSingleton()->buildTree($groupList, 'group_no', 'group_pno', ManageUserGroupConstant::ROOT_PNO_VALUE);

        // 遍历树
        TreeLogic::getSingleton()->travelTree($groupTree, function (&$vo) {
            $vo['expanded'] = true;
            $vo['auth_link'] = Url::build('user_group/auth', ['data_no' => $vo['group_no']]);
        });

        return $groupTree;
    }

    /**
     * 获取群组的菜单树
     *
     * @param string $groupNo
     * @return \cms\core\objects\ReturnObject
     */
    public function getGroupMenuTree($groupNo)
    {
        $group = ManageUserGroupData::getSingleton()->getGroup($groupNo);
        if (empty($group)) {
            return $this->returnError('群组不存在');
        }

        $isRootGroup = $group['group_pno'] === ManageUserGroupConstant::ROOT_PNO_VALUE;
        $menuNos = ManageMenuLinkData::getSingleton()->getGroupMenuNos($groupNo);
        if ($isRootGroup) {
            $pMenuNos = [];
        } else {
            $pMenuNos = ManageMenuLinkData::getSingleton()->getGroupMenuNos($group['group_pno']);
        }

        $menuTree = MenuLogic::getSingleton()->getMenuTree();
        TreeLogic::getSingleton()->travelTree($menuTree, function (&$vo) use ($isRootGroup, $menuNos, $pMenuNos) {
            $menuNo = $vo['menu_no'];
            if ($isRootGroup || in_array($menuNo, $pMenuNos)) {
                $vo['selected'] = in_array($menuNo, $menuNos);
            } else {
                $vo['unselectableStatus'] = true;
            }
        });

        return $this->returnSuccess('获取成功', $menuTree);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DragLogic::onDragOver()
     */
    public function onDragOver($fromNo, $toNo)
    {
        $manageUserGroupData = ManageUserGroupData::getSingleton();
        $groupSort = $manageUserGroupData->getMaxGroupSort($toNo);
        $data = [
            'group_pno' => $toNo,
            'group_sort' => $groupSort + 1
        ];
        if ($manageUserGroupData->updateGroup($fromNo, $data)) {
            return $this->returnSuccess('操作成功');
        } else {
            return $this->returnError('操作失败');
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DragLogic::onDragSide()
     */
    public function onDragSide($before, $fromNo, $toNo)
    {
        $manageUserGroupData = ManageUserGroupData::getSingleton();

        // 查找目标群组
        $toGroup = $manageUserGroupData->getGroup($toNo);
        if (empty($toGroup)) {
            return $this->returnError('目标群组不存在');
        }

        // 更新上级群组
        $manageUserGroupData->updateGroupPno($fromNo, $toGroup['group_pno']);

        // 更新群组排序
        $groupSort = 0;
        $groupList = $manageUserGroupData->getSubGroupList($toGroup['group_pno']);
        foreach ($groupList as $vo) {
            if ($vo['group_no'] == $toNo) {
                if ($before) {
                    $manageUserGroupData->updateGroupSort($fromNo, $groupSort++);
                    $manageUserGroupData->updateGroupSort($toNo, $groupSort++);
                } else {
                    $manageUserGroupData->updateGroupSort($toNo, $groupSort++);
                    $manageUserGroupData->updateGroupSort($fromNo, $groupSort++);
                }
            } elseif ($vo['group_no'] != $fromNo) {
                $manageUserGroupData->updateGroupSort($vo['group_no'], $groupSort++);
            }
        }

        return $this->returnSuccess('操作成功');
    }

}