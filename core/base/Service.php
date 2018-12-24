<?php

namespace core\base;

use core\traits\ErrorTrait;
use core\traits\InstanceTrait;

class Service
{
    use InstanceTrait;
    use ErrorTrait;

    /**
     * 默认错误码
     */
    const ERROR_CODE_DEFAULT = 0;
}