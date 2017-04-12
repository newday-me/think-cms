<?php
namespace app\manage\service;

use think\Config;
use think\Request;
use cms\View;
use cms\Service;

class ViewService extends Service
{

    /**
     * 视图对象
     *
     * @var View
     */
    protected $view;

    /**
     * 获取视图
     *
     * @return View
     */
    public function getView()
    {
        if (is_null($this->view)) {
            $this->view = new View();
            
            // 路径等变量赋值
            $this->assignVariables();
            
            // 设置渲染模板
            $this->setTemplate();
            
            // 设置是否压缩内容
            $this->setMinify();
        }
        return $this->view;
    }

    /**
     * 路径等变量赋值
     *
     * @return void
     */
    protected function assignVariables()
    {
        $request = Request::instance();
        $siteBase = Config::get('site_base') ?: '/';
        $siteVersion = Config::get('site_version') ?: date('Y-m-d');
        
        // 网站版本
        $this->view->assign('site_version', $siteVersion);
        
        // 静态路径
        $staticPath = $siteBase . 'static';
        $this->view->assign('static_path', $staticPath);
        
        // 模块路径
        $modulePath = $staticPath . '/' . $request->module();
        $this->view->assign('module_path', $modulePath);
        
        // 静态库路径
        $libPath = $siteBase . 'lib';
        $this->view->assign('lib_path', $libPath);
    }

    /**
     * 设置是否压缩输出
     *
     * @return void
     */
    protected function setMinify()
    {
        $isMinify = Config::get('manage_html_minify') ? true : false;
        $this->view->setMinify($isMinify);
    }

    /**
     * 设置渲染模板
     *
     * @return void
     */
    protected function setTemplate()
    {
        $template = 'manage@common/jump';
        $this->view->setTemplate(0, $template);
        $this->view->setTemplate(1, $template);
    }
}