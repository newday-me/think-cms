<?php

namespace app\manage\controller;

use think\facade\App;
use think\facade\Request;

class Loader
{

    /**
     * 调用module
     */
    public function run()
    {
        // 模块变量
        define('_MODULE_', Request::param('_module_'));
        define('_CONTROLLER_', Request::param('_controller_'));
        define('_ACTION_', Request::param('_action_'));

        // 执行操作
        $class = 'app\\module\\' . _MODULE_ . '\\controller\\' . \think\Loader::parseName(_CONTROLLER_, 1);
        return App::container()->invokeMethod([
            $class,
            _ACTION_
        ]);
    }

}