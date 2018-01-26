<?php

namespace app\manage\controller;

use app\manage\logic\WidgetLogic;
use app\manage\service\UserLoginService;
use think\facade\Url;

class UserLogin extends Base
{

    /**
     * 登录日志
     */
    public function index()
    {
        $this->assign('site_title', '登录日志');
        $widget = WidgetLogic::getSingleton()->getWidget();

        // 时间段
        $dateRange = $this->request->param('date_range', '');
        $dateRangeHtml = $widget->search('date_range', [
            'name' => 'date_range',
            'value' => $dateRange,
            'holder' => '时间段'
        ]);
        $this->assign('date_rage_html', $dateRangeHtml);

        // 关键字
        $keyword = $this->request->param('keyword');
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
            'holder' => '关键字...'
        ]);
        $this->assign('keyword_html', $keywordHtml);

        $nowPage = $this->request->param('page', 1);
        list($list, $page) = UserLoginService::getSingleton()->getLogListPage($dateRange, $keyword, $nowPage, 10);
        $this->assign('list', $list);
        $this->assign('page', $page);

        // 操作
        $actionList = [
            'search' => Url::build('index')
        ];
        $this->assign('action_list', $actionList);

        return $this->fetch();
    }
}