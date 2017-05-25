<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\JobModel;

class CreateTableManageJob extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(JobModel::getInstance()->getTableShortName());
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
        
        // 队列名
        $job = Column::string('queue', 250)->setComment('队列名');
        $table->addColumn($job);
        
        // 数据
        $payload = Column::longText('payload')->setComment('数据');
        $table->addColumn($payload);
        
        // 尝试次数
        $attempts = Column::integer('attempts')->setLimit(5)->setComment('尝试次数');
        $table->addColumn($attempts);
        
        // 发布次数
        $reserved = Column::integer('reserved')->setLimit(5)->setComment('发布次数');
        $table->addColumn($reserved);
        
        // 发布时间
        $reservedAt = Column::integer('reserved_at')->setNullable()->setComment('发布时间');
        $table->addColumn($reservedAt);
        
        // 执行时间
        $availableAt = Column::integer('available_at')->setComment('执行时间');
        $table->addColumn($availableAt);
        
        // 创建时间
        $createdAt = Column::integer('created_at')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createdAt);
        
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
