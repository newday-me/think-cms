<?php
namespace app\index\controller;

use think\Config;
use cms\Controller;
use cms\Response;
use app\manage\service\ViewService;
use app\common\App;

class Index extends Controller
{

    /**
     * 首页
     *
     * @return string
     */
    public function index()
    {
        $site_info = [
            'site_title' => Config::get('site_title'),
            'site_keyword' => Config::get('site_keyword'),
            'site_description' => Config::get('site_description')
        ];
        $this->assign('site_info', $site_info);
        
        return $this->fetch();
    }

    /**
     * 下载
     *
     * @return void
     */
    public function download()
    {
        $downloadUrl = Config::get('cms_file');
        $downloadUrl || $downloadUrl = 'https://github.com/newday-me/think-cms/archive/master.zip';
        Response::getSingleton()->redirect($downloadUrl, false);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::getView()
     */
    protected function getView()
    {
        return ViewService::getSingleton()->getView();
    }
}
 