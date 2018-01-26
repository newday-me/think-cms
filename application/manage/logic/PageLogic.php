<?php

namespace app\manage\logic;

use think\facade\Request;
use think\facade\Url;
use think\paginator\driver\Bootstrap;
use core\base\Logic;

class PageLogic extends Logic
{

    /**
     * 构造分页
     *
     * @param int $total
     * @param int $nowPage
     * @param int $pageSize
     * @return Bootstrap
     */
    public function buildPage($total, $nowPage, $pageSize = 10)
    {
        $param = Request::param();
        unset($param['page']);

        $action = MenuLogic::getSingleton()->getCurrentAction();
        if (defined('_MODULE_')) {
            unset($param['_module_']);
            unset($param['_controller_']);
            unset($param['_action_']);
            $path = Url::build('@' . $action);
        } else {
            $path = Url::build($action);
        }

        $pagination = new Bootstrap([], $pageSize, $nowPage, $total, false, [
            'path' => $path,
            'query' => $param
        ]);
        return $pagination;
    }

}