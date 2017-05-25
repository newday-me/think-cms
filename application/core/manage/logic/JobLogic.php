<?php
namespace core\manage\logic;

use core\Logic;
use core\manage\model\JobModel;

class JobLogic extends Logic
{

    /**
     * 获取队列下拉
     *
     * @return array
     */
    public function getSelectQueue()
    {
        return JobModel::getInstance()->field('queue as name, queue as value')
            ->group('queue')
            ->select();
        ;
    }

    /**
     * 获取状态下拉
     *
     * @return array
     */
    public function getSelectStatus()
    {
        return [
            [
                'name' => '已执行',
                'value' => 1
            ],
            [
                'name' => '待执行',
                'value' => 0
            ]
        ];
    }

}