<?php
use think\migration\Migrator;
use think\migration\db\Column;
use core\manage\model\UserLoginModel;

class CreateTableManageUserLogin extends Migrator
{

    /**
     * 获取数据表
     *
     * @return \think\migration\db\Table
     */
    public function getTable()
    {
        return $this->table(UserLoginModel::getInstance()->getTableShortName());
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
        $loginUid = Column::integer('login_uid')->setComment('用户ID');
        $table->addColumn($loginUid);
        
        // 登录IP
        $loginIp = Column::string('login_ip', 20)->setDefault('')->setComment('登录IP');
        $table->addColumn($loginIp);
        
        // 浏览器信息
        $loginAgent = Column::string('login_agent', 250)->setDefault('')->setComment('浏览器信息');
        $table->addColumn($loginAgent);
        
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
