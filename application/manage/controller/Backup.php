<?php
namespace app\manage\controller;

use cms\Common;
use core\manage\model\BackupModel;
use core\manage\logic\BackupLogic;

class Backup extends Base
{

    /**
     * 列表
     *
     * @return string
     */
    public function index()
    {
        $this->siteTitle = '备份数据';
        
        $list = BackupLogic::getSingleton()->getTableList();
        $this->_list($list, function (&$list) {
            $common = Common::getSingleton();
            foreach ($list as &$vo) {
                $vo['data_size'] = $common->formatBytes($vo['data_length']);
            }
        });
        
        return $this->fetch();
    }

    /**
     * 备份记录
     *
     * @return string
     */
    public function log()
    {
        $this->siteTitle = '备份记录';
        
        $model = BackupModel::getInstance()->with('user')->order('id desc');
        $this->_page($model, null, function (&$list) {
            foreach ($list as &$vo) {
                $vo['user_nick'] = $vo->user ? $vo->user['user_nick'] : '未知';
            }
        });
        
        return $this->fetch();
    }

    /**
     * 备份表
     *
     * @return void
     */
    public function bakup()
    {
        BackupLogic::getSingleton()->addBakup($this->userId);
        $this->success('备份表成功', 'backup/log');
    }

    /**
     * 优化表
     *
     * @return void
     */
    public function optimize()
    {
        BackupLogic::getSingleton()->optimize();
        $this->success('优化表成功', self::JUMP_REFRESH);
    }

    /**
     * 修复表
     *
     * @return void
     */
    public function repair()
    {
        BackupLogic::getSingleton()->repair();
        $this->success('修复表成功', self::JUMP_REFRESH);
    }

    /**
     * 删除记录
     *
     * @return void
     */
    public function delete()
    {
        BackupLogic::getSingleton()->deleteBakup($this->_id());
        $this->success('删除备份成功', self::JUMP_REFRESH);
    }
}