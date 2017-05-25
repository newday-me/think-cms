<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\BackupModel;

class CreateTableManageBackup extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(BackupModel::getInstance()->getTableShortName());
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::up()
     */
    public function up()
    {
        $table = $this->getTable();
        
        // 用户ID
        $backupUid = Column::integer('backup_uid')->setComment('用户ID');
        $table->addColumn($backupUid);
        
        // 文件大小
        $backupSize = Column::integer('backup_size')->setDefault(0)->setComment('文件大小');
        $table->addColumn($backupSize);
        
        // 导出文件
        $backupFile = Column::string('backup_file', 150)->setComment('导出文件');
        $table->addColumn($backupFile);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 保存
        $table->save();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $this->getTable()->drop();
    }
}
