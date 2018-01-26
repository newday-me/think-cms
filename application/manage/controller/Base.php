<?php

namespace app\manage\controller;

use think\facade\Url;
use cms\Controller;
use app\manage\service\AuthService;
use app\manage\service\LoginService;
use app\manage\service\MenuService;
use app\manage\service\UserService;

class Base extends Controller
{

    /**
     * 初始化
     */
    protected function _initialize()
    {
        $this->checkAuth();
    }

    /**
     * 验证权限
     */
    protected function checkAuth()
    {
        $publicAction = [
            'manage.start.*',
            'manage.captcha.*'
        ];
        if (!AuthService::getSingleton()->isPublicAction($publicAction)) {
            $userNo = LoginService::getSingleton()->getLoginUserNo();
            if (empty($userNo)) {
                $this->error('未登录或者身份已过期', Url::build('manage/start/index'));
            }

            if (!AuthService::getSingleton()->isAuthAction($userNo)) {
                $this->error('你没有权限访问该页面');
            }

            $menuTree = MenuService::getSingleton()->getSideMenu($userNo);
            $this->assign('menu_tree', $menuTree);

            $return = UserService::getSingleton()->getUser($userNo);
            $this->assign('manage_user', $return->getData());
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::beforeViewRender()
     */
    protected function beforeViewRender()
    {
        $siteVersion = date('Ymd');
        $this->assign('site_version', $siteVersion);

        $staticPath = '/static';
        $this->assign('static_path', $staticPath);

        $assetsPath = '/assets';
        $this->assign('assets_path', $assetsPath);

        parent::beforeViewRender();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::getJumpTemplate()
     */
    protected function getJumpTemplate($code)
    {
        return 'common/jump';
    }

}