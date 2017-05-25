<?php
namespace core\blog\logic;

use core\Logic;

class PageLogic extends Logic
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
                'name' => '菜单项',
                'value' => 2
            ],
            [
                'name' => '发布',
                'value' => 1
            ],
            [
                'name' => '待发布',
                'value' => 0
            ]
        ];
    }
}