<?php

/**
 * 模块链接
 *
 * @param string $name            
 * @return string
 */
function module_url($url = '', $vars = '', $suffix = true, $domain = false)
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
    return \think\Url::build($url, $vars, $suffix, $domain);
}