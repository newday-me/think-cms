<?php

namespace core\base;

use cms\core\traits\InstanceTrait;

class Data
{
    // 实例trait
    use InstanceTrait;

    /**
     * 列表转数组
     *
     * @param array $list
     * @return array
     */
    protected function listToArray($list)
    {
        $result = [];
        foreach ($list as $vo) {
            $result[] = $this->recordToArray($vo);
        }
        return $result;
    }

    /**
     * 记录转数组
     *
     * @param mixed $record
     * @return array|null
     */
    public function recordToArray($record)
    {
        if ($record && is_object($record)) {
            return $record->toArray();
        } else {
            return $record;
        }
    }

}