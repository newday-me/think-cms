<?php

namespace core\base;

use think\Collection;
use core\traits\InstanceTrait;

class Data
{
    use InstanceTrait;

    /**
     * 转换为数组
     *
     * @param mixed $record
     * @return array|null
     */
    protected function transToArray($record)
    {
        if ($record instanceof Collection || $record instanceof Model) {
            return $record->toArray();
        } else {
            return $record;
        }
    }
}