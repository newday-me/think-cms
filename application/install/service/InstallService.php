<?php
namespace app\install\service;

use think\Db;
use think\Request;
use cms\Service;
use core\manage\model\ConfigModel;
use core\manage\logic\UserLogic;
use core\manage\validate\UserValidate;

class InstallService extends Service
{

    /**
     * 是否安装
     *
     * @return boolean
     */
    public function isInstall()
    {
        try {
            ConfigModel::getInstance()->count();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 数据库配置模板路径
     *
     * @return string
     */
    public function getSampleFile()
    {
        return ROOT_PATH . 'database' . DS . 'database.php';
    }

    /**
     * SQL文件路径
     *
     * @return string
     */
    public function getSqlFile()
    {
        return ROOT_PATH . 'database' . DS . 'database.sql';
    }

    /**
     * 数据库配置路径
     *
     * @return string
     */
    public function getDatabaseFile()
    {
        return APP_PATH . 'extra' . DS . 'database.php';
    }

    /**
     * 执行安装
     *
     * @throws \Exception
     */
    public function doInstall()
    {
        $request = Request::instance();
        
        // 表单-数据库
        $dbHost = $request->param('db_host');
        $dbDatabase = $request->param('db_database');
        $dbUser = $request->param('db_user');
        $dbPasswd = $request->param('db_passwd');
        $dbPort = $request->param('db_port');
        $dbCharset = $request->param('db_charset');
        $dbPrefix = $request->param('db_prefix');
        
        // 表单-管理员
        $userNick = $request->param('user_nick');
        $userName = $request->param('user_name');
        $userPasswd = $request->param('user_passwd');
        $userPasswdConfirm = $request->param('user_passwd_confirm');
        
        // 验证用户
        $validate = UserValidate::getSingleton();
        $data = [
            'user_name' => $userName,
            'user_nick' => $userNick,
            'user_passwd' => $userPasswd,
            'user_passwd_confirm' => $userPasswdConfirm
        ];
        if (! $validate->scene('install')->check($data)) {
            throw new \Exception($validate->getError());
        }
        
        // 数据库配置
        $sampleFile = $this->getSampleFile();
        $sameleContent = file_get_contents($sampleFile);
        $databaseContent = str_replace([
            '{db_host}',
            '{db_database}',
            '{db_user}',
            '{db_passwd}',
            '{db_port}',
            '{db_charset}',
            '{db_prefix}'
        ], [
            $dbHost,
            $dbDatabase,
            $dbUser,
            $dbPasswd,
            $dbPort,
            $dbCharset,
            $dbPrefix
        ], $sameleContent);
        
        // 连接数据库
        $database = eval(str_replace('<?php', '', $databaseContent));
        $db = Db::connect($database, true);
        
        // 读取SQL
        $sqlFile = $this->getSqlFile();
        $sql = file_get_contents($sqlFile);
        $sql = str_replace("\r", "", $sql);
        $sql = str_replace('`nd_', '`' . $dbPrefix, $sql);
        $sqlArr = explode(";\n", $sql);
        
        // 执行SQL
        try {
            foreach ($sqlArr as $co => $vo) {
                $vo && $db->execute($vo);
            }
        } catch (\Exception $e) {}
        
        // 修改账号
        $map = [
            'id' => 1
        ];
        $data = [
            'user_name' => $userName,
            'user_nick' => $userNick,
            'user_passwd' => UserLogic::getSingleton()->encryptPasswd($userPasswd)
        ];
        Db::connect($database, true)->name('manage_user')
            ->where($map)
            ->update($data);
        
        // 写入配置
        $databaseFile = $this->getDatabaseFile();
        file_put_contents($databaseFile, $databaseContent);
    }
}