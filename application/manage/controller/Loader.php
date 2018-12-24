<?php

namespace app\manage\controller;

use think\facade\App;
use think\facade\Request;

class Loader
{

    /**
     * 调用module
     * @throws
     */
    public function run()
    {
        // 模块变量
        define('_MODULE_', \think\Loader::parseName(Request::param('_module_')));
        define('_CONTROLLER_', \think\Loader::parseName(Request::param('_controller_')));
        define('_ACTION_', \think\Loader::parseName(Request::param('_action_')));

        // 执行操作
        $class = 'app\\module\\' . _MODULE_ . '\\controller\\' . \think\Loader::parseName(_CONTROLLER_, 1);
        return App::getInstance()->invokeMethod([
            $class,
            \think\Loader::parseName(_ACTION_, 1)
        ]);
    }

}