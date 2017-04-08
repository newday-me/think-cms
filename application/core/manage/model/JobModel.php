<?php
namespace core\manage\model;

use core\Model;

class JobModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_job';

    /**
     * 获取队列列表
     *
     * @return array
     */
    public function getQueueList()
    {
        return $this->field('queue as name, queue as value')
            ->group('queue')
            ->select();
        ;
    }

    /**
     * 获取状态列表
     *
     * @return array
     */
    public function getStatusList()
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