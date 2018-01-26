<?php

namespace app\manage\service;

use think\Db;
use think\facade\Env;
use think\facade\Request;
use think\App;
use cms\support\Util;
use core\base\Service;

class IndexService extends Service
{
    /**
     * 获取服务器信息
     *
     * @return array
     */
    public function getServerInfo()
    {
        $util = Util::getSingleton();
        $mysqlVersion = Db::query('select version() as version');
        $serverInfo = [
            'ThinkPHP版本' => 'ThinkPHP ' . App::VERSION,
            'CMS信息' => '作者 : <a class="am-text-success" target="new" href="http://www.newday.me">哩呵</a> , GIT : <a class="am-text-success" target="new" href="https://github.com/newday-me/think-cms">NewDayCms</a>。',
            '操作系统' => PHP_OS,
            'PHP运行方式' => php_sapi_name(),
            'MYSQL版本' => 'MYSQL ' . $mysqlVersion[0]['version'],
            '服务信息' => Request::server('SERVER_SOFTWARE'),
            '主机名信息' => Request::server('SERVER_NAME') . ' (' . Request::server('SERVER_ADDR') . ':' . Request::server('SERVER_PORT') . ')',
            '请求头' => Request::server('HTTP_USER_AGENT'),
            '请求IP' => Request::server('REMOTE_ADDR'),
            '项目路径' => Env::get('ROOT_PATH'),
            '缓存路径' => Env::get('RUNTIME_PATH'),
            '上传限制' => ini_get('upload_max_filesize'),
            'POST限制' => ini_get('post_max_size'),
            '最大内存' => ini_get('memory_limit'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '内存使用' => $util->formatBytes(@memory_get_usage()),
            '磁盘使用' => $util->formatBytes(@disk_free_space('.')) . '/' . $util->formatBytes(@disk_total_space('.')),
            'display_errors' => ini_get("display_errors") == "1" ? '√' : '×',
            'register_globals' => get_cfg_var("register_globals") == "1" ? '√' : '×',
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? '√' : '×',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×'
        ];
        return $serverInfo;
    }
}