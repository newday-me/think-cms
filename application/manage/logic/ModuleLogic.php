<?php

namespace app\manage\logic;

use think\facade\Url;
use core\base\Logic;

class ModuleLogic extends Logic
{

    /**
     * 构造链接
     *
     * @param string $url
     * @param string $vars
     * @param bool $suffix
     * @param bool $domain
     * @return string
     */
    public function url($url = '', $vars = '', $suffix = true, $domain = false)
    {
        if (defined('_MODULE_') && strpos($url, '@') === false) {
            $arr = array_filter(explode('/', $url));
            switch (count($arr)) {
                case 0:
                    $url = _MODULE_ . '/' . _CONTROLLER_ . '/' . _ACTION_;
                    break;
                case 1:
                    $url = _MODULE_ . '/' . _CONTROLLER_ . '/' . $arr[0];
                    break;
                case 2:
                    $url = _MODULE_ . '/' . $arr[0] . '/' . $arr[1];
                    break;
                default:
                    $url = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
                    break;
            }
            $url = '@module/' . $url;
        }
        return Url::build($url, $vars, $suffix, $domain);
    }

}