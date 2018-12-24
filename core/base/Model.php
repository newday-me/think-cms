<?php

namespace core\base;

use core\traits\InstanceTrait;

class Model extends \think\Model
{
    // 实例trait
    use InstanceTrait;

    /**
     * 分区数
     *
     * @var int
     */
    protected $partitionNum = 1;

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

    /**
     * 新增记录
     *
     * @param array $data
     * @return $this
     */
    public function add($data)
    {
        $this->isUpdate(false)->save($data);
        return $this;
    }

    /**
     * 新的唯一值
     *
     * @param string $field
     * @param int $length
     * @param string $prefix
     * @return string
     * @throws
     */
    public function newUniqueAttr($field, $length = 16, $prefix = '')
    {
        $uniqueAttr = $this->randHashStr($length, $prefix);
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
     * 获取分区数
     *
     * @return int
     */
    public function getPartitionNum()
    {
        return $this->partitionNum;
    }

    /**
     * 获取分区实例
     *
     * @param string $value
     *
     * @return self
     */
    public static function getInstancePartition($value)
    {
        $instance = self::getInstance();
        if ($instance->partitionNum > 1) {
            $index = self::getPartitionIndex($value, $instance->partitionNum);
            $instance->table = $instance->getTableName() . '_' . $index;
        }
        return $instance;
    }

    /**
     * 获取所在分区
     *
     * @param string $value
     * @param int $total
     * @return int
     */
    public static function getPartitionIndex($value, $total)
    {
        $sum = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            $sum += ord($value[$i]);
        }
        return $sum % $total + 1;
    }

    /**
     * 随机哈希字符串
     *
     * @param int $length
     * @param string $prefix
     * @return string
     */
    private function randHashStr($length = 16, $prefix = '')
    {
        $size = $length - strlen($prefix);
        $offset = intval(32 - $size) / 2;
        return $prefix . substr(md5(microtime(true) . mt_rand(1000, 9999)), $offset, $size);
    }
}