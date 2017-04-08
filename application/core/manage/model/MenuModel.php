<?php
namespace core\manage\model;

use core\Model;

class MenuModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_menu';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 获取分组列表
     *
     * @param array $map            
     */
    public function getGroupList($map = [])
    {
        return $this->field('id, menu_group')
            ->where($map)
            ->group('menu_group')
            ->order('menu_sort asc')
            ->select();
    }

    /**
     * 获取打开方式列表
     *
     * @return array
     */
    public function getTargetList()
    {
        return [
            [
                'name' => '当前窗口',
                'value' => '_self'
            ],
            [
                'name' => '新窗口',
                'value' => '_blank'
            ]
        ];
    }

    /**
     * 获取链接Build列表
     *
     * @return array
     */
    public function getBuildList()
    {
        return [
            [
                'name' => '需要',
                'value' => 1
            ],
            [
                'name' => '不需要',
                'value' => 0
            ]
        ];
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
                'name' => '显示',
                'value' => 1
            ],
            [
                'name' => '隐藏',
                'value' => 0
            ]
        ];
    }

}