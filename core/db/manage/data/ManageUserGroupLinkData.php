<?php

namespace core\db\manage\data;

use core\base\Data;
use core\db\manage\model\ManageUserGroupLinkModel;

class ManageUserGroupLinkData extends Data
{

    /**
     * 保存用户分组
     *
     * @param string $userNo
     * @param array $groupNos
     * @return int
     */
    public function saveUserGroupNos($userNo, $groupNos)
    {
        $oldGroupNos = $this->getUserGroupNos($userNo);

        $addGroupNos = array_diff($groupNos, $oldGroupNos);
        $this->createLinkBatch($userNo, $addGroupNos);

        $deleteGroupNos = array_diff($oldGroupNos, $groupNos);
        return $this->deleteLinkBatch($userNo, $deleteGroupNos);
    }

    /**
     * 获取
     *
     * @param string $userNo
     * @return array
     */
    public function getUserGroupNos($userNo)
    {
        $map = [
            'user_no' => $userNo
        ];
        $list = ManageUserGroupLinkModel::getSingleton()->where($map)->select();

        $groupNos = [];
        foreach ($list as $vo) {
            $groupNos[] = $vo['group_no'];
        }
        return $groupNos;
    }

    /**
     * 批量创建连接
     *
     * @param string $userNo
     * @param array $groupNos
     */
    public function createLinkBatch($userNo, $groupNos)
    {
        foreach ($groupNos as $groupNo) {
            ManageUserGroupLinkModel::create([
                'user_no' => $userNo,
                'group_no' => $groupNo
            ]);
        }
    }

    /**
     * 批量删除连接
     *
     * @param string $userNo
     * @param array $groupNos
     * @return int
     */
    public function deleteLinkBatch($userNo, $groupNos)
    {
        $map = [
            'user_no' => $userNo
        ];
        return ManageUserGroupLinkModel::getInstance()->where($map)->where('group_no', 'in', $groupNos)->delete();
    }

}