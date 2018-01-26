<?php

namespace app\manage\controller;

use app\manage\logic\LoginLogic;
use app\manage\service\CaptchaService;
use think\facade\Url;
use app\manage\service\StartService;

class Start extends Base
{

    /**
     * 开始
     *
     * @return string
     */
    public function index()
    {
        // 网站标题
        $this->assign('site_title', '开始使用');

        // 跳转链接
        $this->assign('login_url', Url::build('login'));

        // 验证码图片
        $this->assign('captcha_src', Url::build('captcha/index'));

        return $this->fetch();
    }

    /**
     * 登录
     */
    public function login()
    {
        $userName = $this->request->param('user_name');
        if (empty($userName)) {
            $this->error('用户名为空');
        }

        $userPassword = $this->request->param('user_password');
        if (empty($userPassword)) {
            $this->error('密码为空');
        }

        $verifyCode = $this->request->param('verify_code');
        if (empty($verifyCode)) {
            $this->error('验证码为空');
        } elseif (!CaptchaService::getSingleton()->check($verifyCode)) {
            $this->error('验证码错误');
        }

        $return = StartService::getSingleton()->login($userName, $userPassword);
        if ($return->isSuccess()) {
            $data = $return->getData();
            $this->success($return->getMsg(), $data['home_url']);
        } else {
            $this->error($return->getMsg());
        }
    }

    /**
     * 安全退出
     */
    public function logout()
    {
        StartService::getSingleton()->logout();
        $this->success('退出成功', Url::build('manage/start/index'));
    }

}