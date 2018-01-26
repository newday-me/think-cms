<?php

namespace app\manage\service;

use think\db\Query;
use core\base\Service;
use core\db\manage\data\ManageUserLoginData;
use app\manage\logic\PageLogic;

class UserLoginService extends Service
{

    /**
     * 获取分页列表
     *
     * @param string $dateRange
     * @param string $keyword
     * @param int $nowPage
     * @param int $pageSize
     * @return array($list, $page)
     */
    public function getLogListPage($dateRange, $keyword, $nowPage, $pageSize)
    {
        $closure = function (Query $query) use ($dateRange, $keyword) {
            if ($dateRange) {
                list($startDate, $endDate) = explode(' - ', $dateRange);
                $startTime = strtotime($startDate);
                $endTime = strtotime($endDate) + 86400 - 1;
                $query->where('create_time', 'between', [$startTime, $endTime]);
            }

            if ($keyword) {
                $query->where('login_agent', 'like', '%' . $keyword . '%');
            }
        };

        $list = ManageUserLoginData::getSingleton()->getLogListPage($closure, $nowPage, $pageSize);
        foreach ($list as &$vo) {
            if ($vo['user']) {
                $vo['user_nick'] = $vo['user']['user_nick'];
            } else {
                $vo['user_nick'] = '未知';
            }
        }
        unset($vo);

        $total = ManageUserLoginData::getSingleton()->getLogListCount($closure);
        $page = PageLogic::getSingleton()->buildPage($total, $nowPage, $pageSize);

        return [$list, $page->render()];
    }

}