<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * App对象
 *
 * @return \app\common\App
 */
function app()
{
    return app\common\App::getSingleton();
}

/**
 * 跳转链接
 *
 * @param string $url            
 * @param array $param            
 * @param string $need_build            
 * @throws \think\exception\HttpResponseException
 */
function response_redirect($url, $param = [], $need_build = true)
{
    if ($need_build) {
        $url = \think\Url::build($url, $param);
    } elseif (is_array($param) && count($param)) {
        $url .= strpos($url, '?') ? '&' : '?';
        $url .= http_build_query($param);
    }
    
    $response = new \think\Response();
    $response->header('Location', $url);
    throw new \think\exception\HttpResponseException($response);
}