<?php
namespace core\manage\model;

use core\Model;

class ConfigModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_config';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 使用别名
     *
     * @param unknown $query            
     */
    public function useAlias($query = null)
    {
        is_null($query) && $query = $this;
        return $query->alias('_a_config');
    }

    /**
     * 连接分组
     *
     * @return \think\db\Query
     */
    public function withGroups($query = null)
    {
        $query = $this->useAlias($query);
        return $query->join(ConfigGroupModel::getInstance()->getTableShortName() . ' _a_config_group', '_a_config.config_gid = _a_config_group.id');
    }

}