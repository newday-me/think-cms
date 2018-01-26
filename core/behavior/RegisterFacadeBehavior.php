<?php

namespace core\behavior;

use think\Facade;
use cms\facade\Response;

class RegisterFacadeBehavior
{
    /**
     * 绑定facade
     */
    public static function run()
    {
        Facade::bind([
            Response::class => \cms\Response::class
        ]);
    }
}