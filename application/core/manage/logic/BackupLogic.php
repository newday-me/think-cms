<?php
namespace core\manage\logic;

use think\Db;
use think\Config;
use core\Logic;
use core\manage\model\BackupModel;
use Ifsnop\Mysqldump\Mysqldump;

class BackupLogic extends Logic
{

    /**
     * 数据表
     *
     * @return array
     */
    public function getTableList()
    {
        return array_map('array_change_key_case', Db::query('SHOW TABLE STATUS'));
    }

    /**
     * 备份
     *
     * @param number $userId            
     * @param array $tables            
     * @return array
     */
    public function addBakup($userId, $tables = [])
    {
        $bakupPath = $this->getBakupPath();
        $model = BackupModel::getInstance();
        $dump = $this->getDumper($tables);
        
        // 备份数据库
        $backupFile = '/dump_' . date('Ymd_His') . '.sql';
        $dump->start($bakupPath . $backupFile);
        
        // 备份记录
        $data = [
            'backup_uid' => $userId,
            'backup_size' => filesize($bakupPath . $backupFile),
            'backup_file' => $backupFile
        ];
        return $model->create($data);
    }

    /**
     * 删除备份
     *
     * @param number $id            
     * @return number
     */
    public function deleteBakup($id)
    {
        $bakupPath = $this->getBakupPath();
        $model = BackupModel::getInstance();
        
        $bakup = $model->get($id);
        @unlink($bakupPath . $bakup['backup_file']);
        return $bakup->delete();
    }

    /**
     * 优化表
     *
     * @return void
     */
    public function optimize()
    {
        $list = $this->getTableList();
        foreach ($list as $vo) {
            Db::query('OPTIMIZE TABLE `' . $vo['name'] . '`');
        }
    }

    /**
     * 修复表
     *
     * @return void
     */
    public function repair()
    {
        $list = $this->getTableList();
        foreach ($list as $vo) {
            Db::query('REPAIR TABLE `' . $vo['name'] . '`');
        }
    }

    /**
     * 获取导出对象
     *
     * @param array $tables            
     * @return Mysqldump
     */
    public function getDumper($tables = [])
    {
        $database = Config::get('database');
        $dsn = 'mysql:host=' . $database['hostname'] . ';port=' . $database['hostport'] . ';dbname=' . $database['database'];
        $dumpSetting = [
            'default-character-set' => $database['charset'],
            'add-drop-table' => true,
            'skip-comments' => true,
            'lock-tables' => false,
            'add-locks' => false,
            'include-tables' => $tables
        ];
        return new Mysqldump($dsn, $database['username'], $database['password'], $dumpSetting);
    }

    /**
     * 备份路径
     *
     * @return atring
     */
    public function getBakupPath()
    {
        return Config::get('bakup_path') ? Config::get('bakup_path') : ROOT_PATH . 'database' . DS . 'backups';
    }
}