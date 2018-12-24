<?php

namespace app\index\controller;

use think\facade\Url;
use core\support\Controller;
use core\support\Response;

class Index extends Controller
{
    /**
     * 首页
     *
     * @return string
     */
    public function index()
    {
        $siteInfo = [
            'site_title' => 'NewDayCms - 哩呵后台管理系统',
            'site_keyword' => '哩呵,CMS,ThinkPHP,后台,管理系统',
            'site_description' => 'NewdayCms ，简单的方式管理数据。期待你的参与，共同打造一个功能更强大的通用后台管理系统。',
            'manage_url' => Url::build('manage/index/index')
        ];
        $this->assign('site_info', $siteInfo);

        return $this->fetch();
    }

    /**
     * 下载
     *
     * @return void
     */
    public function download()
    {
        $downloadUrl = 'http://static.newday.me/cms/2.0.0.zip';
        Response::getSingleton()->redirect($downloadUrl, false);
    }
}