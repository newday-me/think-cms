<?php

namespace core\traits;

trait InstanceTrait
{

    /**
     * 获取实例
     *
     * @return $this
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * 获取单实例
     *
     * @return $this
     */
    public static function getSingleton()
    {
        static $instance = null;

        if (!isset($instance)) {
            $instance = self::getInstance();
        }

        return $instance;
    }

}