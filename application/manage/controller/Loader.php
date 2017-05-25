<?php
namespace app\manage\controller;

use think\App;
use think\Request;

class Loader
{

    /**
     * 调用module
     *
     * @param Request $request            
     * @return mixed
     */
    public function run(Request $request)
    {
        
        // 模块变量
        define('_MODULE_', $request->param('_module_'));
        define('_CONTROLLER_', $request->param('_controller_'));
        define('_ACTION_', $request->param('_action_'));
        
        // 后台的view_path
        $manageViewPath = APP_PATH . 'manage/view/';
        define('MANAGE_VIEW_PATH', $manageViewPath);
        
        // 模块的view_path
        $moduleViewPath = APP_PATH . 'module/' . _MODULE_ . '/view/';
        define('MODULE_VIEW_PATH', $moduleViewPath);
        
        // 加载配置
        $moduleConfigFile = APP_PATH . 'module/' . _MODULE_ . '/config.php';
        if (is_file($moduleConfigFile)) {
            $config = require ($moduleConfigFile);
            $config && Config::set($config);
        }
        
        // 执行操作
        $class = 'module\\' . _MODULE_ . '\\controller\\' . \think\Loader::parseName(_CONTROLLER_, 1);
        return App::invokeMethod([
            $class,
            _ACTION_
        ]);
    }
}