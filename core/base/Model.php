<?php

namespace core\base;

use cms\support\Util;
use cms\core\traits\InstanceTrait;

class Model extends \think\Model
{
    // 实例trait
    use InstanceTrait;

    /**
     * 新的唯一值
     *
     * @param $field
     * @param int $length
     * @param string $prefix
     * @return string
     */
    public function newUniqueAttr($field, $length = 16, $prefix = '')
    {
        $uniqueAttr = Util::getSingleton()->randHashStr($length, $prefix);
        $map = [
            $field => $uniqueAttr
        ];
        $record = $this->where($map)->field('id')->find();
        if (empty($record)) {
            return $uniqueAttr;
        } else {
            return $this->newUniqueAttr($field, $length, $prefix);
        }
    }

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTableName()
    {
        if ($this->table) {
            return $this->table;
        } else {
            return $this->getConfig('prefix') . $this->getName();
        }
    }
}