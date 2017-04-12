<?php
namespace app\manage\service;

use think\Db;
use think\Request;
use cms\Service;
use cms\Common;
use core\manage\model\UserModel;
use core\manage\model\MenuModel;
use core\manage\model\ConfigModel;
use core\manage\model\FileModel;

class IndexService extends Service
{

    /**
     * 获取网站统计
     *
     * @return array
     */
    public function getSiteStat()
    {
        return [
            'user_num' => UserModel::getInstance()->count(),
            'menu_num' => MenuModel::getInstance()->count(),
            'config_num' => ConfigModel::getInstance()->count(),
            'file_num' => FileModel::getInstance()->count()
        ];
    }

    /**
     * 获取服务器信息
     *
     * @return array
     */
    public function getServerInfo()
    {
        $request = Request::instance();
        $common = Common::getSingleton();
        $mysqlVersion = Db::query('select version() as version');
        $serverInfo = [
            'ThinkPHP版本' => 'ThinkPHP ' . THINK_VERSION,
            'CMS信息' => '作者 : <a class="am-text-success" target="new" href="https://www.newday.me">哩呵</a> , GIT : <a class="am-text-success" target="new" href="https://github.com/newday-me/think-cms">NewDayCms</a>。',
            '操作系统' => PHP_OS,
            '主机名信息' => $request->server('SERVER_NAME') . ' (' . $request->server('SERVER_ADDR') . ':' . $request->server('SERVER_PORT') . ')',
            '运行环境' => $request->server('SERVER_SOFTWARE'),
            'PHP运行方式' => php_sapi_name(),
            '程序目录' => WEB_PATH,
            'MYSQL版本' => 'MYSQL ' . $mysqlVersion[0]['version'],
            '上传限制' => ini_get('upload_max_filesize'),
            'POST限制' => ini_get('post_max_size'),
            '最大内存' => ini_get('memory_limit'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '内存使用' => $common->formatBytes(@memory_get_usage()),
            '磁盘使用' => $common->formatBytes(@disk_free_space(".")) . '/' . $common->formatBytes(@disk_total_space(".")),
            'display_errors' => ini_get("display_errors") == "1" ? '√' : '×',
            'register_globals' => get_cfg_var("register_globals") == "1" ? '√' : '×',
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? '√' : '×',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×'
        ];
        return $serverInfo;
    }

}