<?php
namespace app\manage\controller;

use think\Url;
use think\Config;
use think\Request;
use app\manage\logic\LoginLogic;

class Start extends Base
{

    /**
     * 验证码Key
     *
     * @var unknown
     */
    const CAPTCHA_KEY = 'captcha_key';

    /**
     * 登录页面
     *
     * @param Request $request            
     * @return string|void
     */
    public function login(Request $request)
    {
        // 是否需要验证码
        $needVerify = Config::get('manage_verify_code');
        
        if ($request->isPost()) {
            $loginLogic = LoginLogic::getSingleton();
            
            // 验证码
            if ($needVerify) {
                $verifyCode = $request->param('verify_code');
                if (empty($verifyCode)) {
                    $this->error('请填写验证码');
                } elseif (! captcha_check($verifyCode, self::CAPTCHA_KEY)) {
                    $this->error('错误的验证码');
                }
            }
            
            // 登录
            $name = $request->param('user_name');
            $passwd = $request->param('user_passwd');
            list ($success, $msg, $url) = $loginLogic->doLogin($name, $passwd);
            if ($success) {
                $this->success($msg, $url);
            } else {
                $this->error($msg);
            }
        } else {
            // 登录链接
            $this->assign('login_url', Url::build('login'));
            
            // 验证码链接
            if ($needVerify) {
                $this->assign('code_url', captcha_src(self::CAPTCHA_KEY));
            }
            
            return $this->fetch();
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        LoginLogic::getSingleton()->loginOut();
        $this->success('退出登录成功', Url::build('start/login'));
    }
}