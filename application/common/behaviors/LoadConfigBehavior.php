<?php
namespace app\common\behaviors;

use think\Config;
use cms\Response;
use think\Request;
use core\manage\logic\ConfigLogic;

class LoadConfigBehavior
{

    /**
     * 加载配置
     *
     * @param array $params            
     *
     * @return void
     */
    public function run(&$params)
    {
        try {
            Config::set(ConfigLogic::getSingleton()->getConfig());
        } catch (\Exception $e) {
            Request::instance()->module() == 'install' || Response::getSingleton()->redirect('/install.html');
        }
    }
}