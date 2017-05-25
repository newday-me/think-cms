<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\FileModel;

class CreateTableManageFile extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(FileModel::getInstance()->getTableShortName());
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
        
        // 文件哈希
        $fileHash = Column::string('file_hash', 32)->setComment('文件哈希');
        $table->addColumn($fileHash);
        
        // 文件后缀
        $fileExt = Column::string('file_ext', 10)->setComment('文件后缀');
        $table->addColumn($fileExt);
        
        // 文件大小
        $fileSize = Column::integer('file_size')->setDefault(0)->setComment('文件大小');
        $table->addColumn($fileSize);
        
        // 文件路径
        $filePath = Column::string('file_path', 100)->setComment('文件路径');
        $table->addColumn($filePath);
        
        // 文件链接
        $fileUrl = Column::string('file_url', 150)->setComment('文件链接');
        $table->addColumn($fileUrl);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 文件哈希，唯一索引
        $table->addIndex('file_hash', [
            'unique' => true
        ]);
        
        // 文件类型，普通索引
        $table->addIndex('file_ext');
        
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
