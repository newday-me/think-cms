<?php
namespace app\manage\behavior;

use think\Url;
use think\Request;
use app\manage\service\LoginService;
use app\manage\service\ViewService;

class DemoBehavior
{

    /**
     * 测试环境
     *
     * @param unknown $params            
     */
    public function run(&$params)
    {
        $login = LoginService::getSingleton();
        
        // 登录
        $user = $login->getLoginUser();
        if (empty($user)) {
            $data = [
                'user_id' => 1,
                'manage_url' => Url::build('index/index')
            ];
            $login->getLoginDriver()->storageLogin(LoginService::LOGIN_KEY, $data);
        }
        
        // 操作
        $request = Request::instance();
        if ($request->module() != 'start' && ($request->isPost() || $request->isAjax())) {
            $view = ViewService::getSingleton()->getView();
            $view->error('测试环境，操作受限');
        }
    }
}