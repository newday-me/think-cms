<?php
namespace app\manage\controller;

use think\Request;
use app\manage\service\IndexService;
use app\manage\service\RuntimeService;

class Index extends Base
{

    /**
     * 首页
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '后台首页';
        $index = IndexService::getSingleton();
        
        // 基础统计
        $siteStat = $index->getSiteStat();
        $this->assign('site_stat', $siteStat);
        
        // 系统信息
        $serverInfo = $index->getServerInfo();
        $this->assign('server_info', $serverInfo);
        
        // 扩展列表
        $extensionsList = get_loaded_extensions();
        $this->assign('extensions_list', implode(' , ', $extensionsList));
        
        return $this->fetch();
    }

    /**
     * 账号设置
     *
     * @param Request $request            
     * @return string
     */
    public function account(Request $request)
    {
        return $this->fetch();
    }

    /**
     * 缓存清理
     *
     * @param Request $request            
     * @return string|void
     */
    public function runtime(Request $request)
    {
        if ($request->isPost()) {
            $runtime = RuntimeService::getSingleton();
            
            $paths = $request->param('path/a', []);
            $deleteEmpty = $request->param('delete_empty', 1);
            $startTime = strtotime($request->param('start_time', ''));
            $endTime = strtotime($request->param('start_time', ''));
            
            $runtime->clearRuntime($paths, [
                'delete_empty' => $deleteEmpty,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
            
            $this->success('清除缓存成功');
        } else {
            // 路径列表
            $pathList = [
                [
                    'name' => '缓存：数据库、变量等缓存',
                    'value' => 'cache'
                ],
                [
                    'name' => '日志：访问、错误、SQL等日志',
                    'value' => 'log'
                ],
                [
                    'name' => '临时：视图模板等临时文件',
                    'value' => 'temp'
                ]
            ];
            $this->assign('path_list', $pathList);
            
            // 删除空文件
            $emptyList = [
                [
                    'name' => '删除',
                    'value' => 1
                ],
                [
                    'name' => '不保留',
                    'value' => 'log'
                ]
            ];
            $this->assign('empty_list', $emptyList);
            
            return $this->fetch();
        }
    }
}