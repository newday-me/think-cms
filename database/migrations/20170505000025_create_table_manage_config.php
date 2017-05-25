<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\ConfigModel;

class CreateTableManageConfig extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(ConfigModel::getInstance()->getTableShortName());
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
        
        // 配置名称
        $configName = Column::string('config_name', 30)->setComment('配置名称');
        $table->addColumn($configName);
        
        // 配置值
        $configValue = Column::string('config_value', 1000)->setDefault('')->setComment('配置值');
        $table->addColumn($configValue);
        
        // 配置类型
        $configType = Column::string('config_type', 10)->setComment('配置类型');
        $table->addColumn($configType);
        
        // 配置标题
        $configTitle = Column::string('config_title', 30)->setDefault('')->setComment('配置标题');
        $table->addColumn($configTitle);
        
        // 配置分组
        $configGid = Column::integer('config_gid')->setDefault(0)->setComment('配置分组');
        $table->addColumn($configGid);
        
        // 额外配置
        $configExtra = Column::text('config_extra')->setComment('额外配置');
        $table->addColumn($configExtra);
        
        // 配置排序
        $configSort = Column::integer('config_sort')->setDefault(0)->setComment('配置排序');
        $table->addColumn($configSort);
        
        // 备注
        $configRemark = Column::string('config_remark', 150)->setDefault('')->setComment('备注');
        $table->addColumn($configRemark);
        
        // 创建时间
        $createTime = Column::integer('create_time')->setDefault(0)->setComment('创建时间');
        $table->addColumn($createTime);
        
        // 更新时间
        $updateTime = Column::integer('update_time')->setDefault(0)->setComment('更新时间');
        $table->addColumn($updateTime);
        
        // 配置名，唯一索引
        $table->addIndex('config_name', [
            'unique' => true
        ]);
        
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
