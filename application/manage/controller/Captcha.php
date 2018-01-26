<?php

namespace app\manage\controller;

use app\manage\service\CaptchaService;

class Captcha extends Base
{

    /**
     * 验证码图片
     *
     * @return \think\Response
     */
    public function index()
    {
        return CaptchaService::getSingleton()->image();
    }

}