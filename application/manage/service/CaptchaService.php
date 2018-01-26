<?php

namespace app\manage\service;

use core\base\Service;

class CaptchaService extends Service
{

    /**
     * key
     *
     * @var string
     */
    const CAPTCHA_KEY = 'api_captcha_common';

    /**
     * 配置
     *
     * @var array
     */
    protected $config = [
        'length' => 4,
        'codeSet' => '0123456789',
        'useCurve' => false
    ];

    /**
     * 图片
     *
     * @return \think\Response
     */
    public function image()
    {
        return captcha(self::CAPTCHA_KEY, $this->config);
    }

    /**
     * 验证
     *
     * @param string $code
     * @return boolean
     */
    public function check($code)
    {
        return captcha_check($code, self::CAPTCHA_KEY);
    }
}