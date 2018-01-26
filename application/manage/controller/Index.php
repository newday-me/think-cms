<?php

namespace app\manage\controller;

use app\manage\service\IndexService;
use app\manage\service\RuntimeService;

class Index extends Base
{
    /**
     * 控制台
     *
     * @return string
     */
    public function index()
    {
        $this->assign('site_title', '控制台');

        // 系统信息
        $serverInfo = IndexService::getSingleton()->getServerInfo();
        $this->assign('server_info', $serverInfo);

        // 扩展列表
        $extensionsList = get_loaded_extensions();
        $this->assign('extensions_list', implode(' , ', $extensionsList));

        return $this->fetch();
    }

    /**
     * 缓存清理
     */
    public function runtime()
    {
        if ($this->request->isPost()) {
            $paths = $this->request->param('path/a', []);
            $deleteEmpty = $this->request->param('delete_empty', 1);
            $startTime = strtotime($this->request->param('start_time', ''));
            $endTime = strtotime($this->request->param('start_time', ''));

            $option = [
                'delete_empty' => $deleteEmpty,
                'start_time' => $startTime,
                'end_time' => $endTime
            ];
            RuntimeService::getSingleton()->clearRuntime($paths, $option);

            $this->success('清除缓存成功');
        } else {
            $this->assign('site_title', '缓存清理');
            return $this->fetch();
        }
    }

}