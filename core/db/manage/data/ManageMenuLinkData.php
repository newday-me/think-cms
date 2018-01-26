<?php

namespace core\db\manage\data;

use core\base\Data;
use core\db\manage\model\ManageMenuLinkModel;

class ManageMenuLinkData extends Data
{

    /**
     * 保存群组菜单
     *
     * @param string $groupNo
     * @param array $menuNos
     */
    public function saveGroupMenuNos($groupNo, $menuNos)
    {
        $oldMenuNos = $this->getGroupMenuNos($groupNo);

        $addMenuNos = array_diff($menuNos, $oldMenuNos);
        $this->createLinkBatch($groupNo, $addMenuNos);

        $removeMenuNos = array_diff($oldMenuNos, $menuNos);
        $this->deleteLinkBatch($groupNo, $removeMenuNos);
    }

    /**
     * 获取群组的菜单编号
     *
     * @param string $groupNo
     * @return array
     */
    public function getGroupMenuNos($groupNo)
    {
        $map = [
            'group_no' => $groupNo
        ];
        $list = ManageMenuLinkModel::getInstance()->where($map)->select();

        $menuNos = [];
        foreach ($list as $vo) {
            $menuNos[] = $vo['menu_no'];
        }
        return $menuNos;
    }

    /**
     * 批量创建连接
     *
     * @param string $groupNo
     * @param array $menuNos
     */
    public function createLinkBatch($groupNo, $menuNos)
    {
        foreach ($menuNos as $menuNo) {
            ManageMenuLinkModel::create([
                'group_no' => $groupNo,
                'menu_no' => $menuNo
            ]);
        }
    }

    /**
     * 批量删除连接
     *
     * @param string $groupNo
     * @param array $menuNos
     * @return int
     */
    public function deleteLinkBatch($groupNo, $menuNos)
    {
        $map = [
            'group_no' => $groupNo
        ];
        return ManageMenuLinkModel::getInstance()->where($map)->where('menu_no', 'in', $menuNos)->delete();
    }

}