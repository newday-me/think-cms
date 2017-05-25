<?php
use think\migration\Migrator;
use think\Db;
use think\Queue;
use core\manage\model\UserLoginModel;
use core\manage\model\JobModel;
use core\manage\model\FileModel;
use core\manage\model\BackupModel;
use core\blog\model\ArticleModel;
use core\blog\model\PageModel;

class InitDemoData extends Migrator
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::up()
     */
    public function up()
    {
        $this->initJob();
        
        $this->initUserLogin();
        
        $this->initFile();
        
        $this->initBackup();
        
        $this->initRecycle();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $tables = [
            JobModel::getInstance()->getTableName(),
            UserLoginModel::getInstance()->getTableName(),
            BackupModel::getInstance()->getTableName()
        ];
        foreach ($tables as $table) {
            Db::connect()->query('truncate table ' . $table);
        }
    }

    /**
     * 初始化任务
     *
     * @return void
     */
    protected function initJob()
    {
        for ($i = 0; $i < 20; $i ++) {
            Queue::push('\\app\\common\\jobs\\EchoJob', [
                'date_time' => date('Y-m-d H:i:s'),
                'rand_num' => rand(1000, 9999)
            ]);
        }
    }

    /**
     * 初始化登录日志
     *
     * @return void
     */
    protected function initUserLogin()
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1'
        ];
        for ($i = 0; $i < 20; $i ++) {
            $agent = $agents[array_rand($agents)];
            $data = [
                'login_uid' => 1,
                'login_ip' => rand(0, 127) . '.' . rand(0, 127) . '.' . rand(0, 127),
                'login_agent' => $agent,
                'create_time' => rand(strtotime('-7 day'), time())
            ];
            UserLoginModel::create($data);
        }
    }

    /**
     * 初始化文件
     *
     * @return void
     */
    protected function initFile()
    {
        $files = [
            'http://img.hb.aicdn.com/bbdd4cd249fa51da57d8e41fe817177ff4b922c2c6a39-CJbmb9_fw658',
            'http://img.hb.aicdn.com/43499d49bb0cfca5cea52508116c60dfd46cb1a777610-RAcGlg_fw658',
            'http://img.hb.aicdn.com/14650b9405443ee27855eef343421d6c25c7cf4c30ae08-jAoFuk_fw658',
            'http://img.hb.aicdn.com/5941593f706f41569c03544001e1cfd0a6c5a127bf42c-0K3wA1_fw658',
            'http://img.hb.aicdn.com/456ca07786ec917ca4f0b87cbda3df9a14482aeaa047c-EgBJiO_fw658'
        ];
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $data = [
                'file_hash' => md5($content),
                'file_ext' => 'jpg',
                'file_size' => strlen($content),
                'file_path' => '/upload/' . md5($content) . '.jpg',
                'file_url' => $file
            ];
            FileModel::create($data);
        }
    }

    /**
     * 初始化备份
     *
     * @return void
     */
    protected function initBackup()
    {
        for ($i = 0; $i < 5; $i ++) {
            $data = [
                'backup_uid' => 1,
                'backup_size' => rand(10000, 100000),
                'backup_file' => 'dump_' . date('Ymd') . '_' . rand(100000, 999999) . '.sql'
            ];
            BackupModel::create($data);
        }
    }

    /**
     * 初始化回收站
     *
     * @return void
     */
    protected function initRecycle()
    {
        $map = [
            'id' => 1
        ];
        ArticleModel::getInstance()->softDelete($map);
        
        $map = [
            'id' => 2
        ];
        PageModel::getInstance()->softDelete($map);
    }
}
