<?php
namespace core;

use think\Config;
use cms\traits\InstanceTrait;

/**
 * Model类
 *
 * @method \think\db\Query field(string $field) 查询字段
 * @method \think\db\Query alias(string $name) 查询别名
 * @method \think\db\Query where(mixed $field, string $op = null, mixed $condition = null) 查询条件
 * @method \think\db\Query join(mixed $join, mixed $condition = null, string $type = 'INNER') JOIN查询
 * @method \think\db\Query union(mixed $union, boolean $all = false) UNION查询
 * @method \think\db\Query limit(mixed $offset, integer $length = null) 查询LIMIT
 * @method \think\db\Query order(mixed $field, string $order = null) 查询ORDER
 * @method \think\db\Query cache(mixed $key = true , integer $expire = null) 设置查询缓存
 * @method mixed value(string $field) 获取某个字段的值
 * @method array column(string $field, string $key = '') 获取某个列的值
 * @method \think\db\Query view(mixed $join, mixed $field = null, mixed $on = null, string $type = 'INNER') 视图查询
 * @method mixed find(mixed $data = []) 查询单个记录
 * @method mixed select(mixed $data = []) 查询多个记录
 * @method integer insert(array $data, boolean $replace = false, boolean $getLastInsID = false, string $sequence = null) 插入一条记录
 * @method integer insertGetId(array $data, boolean $replace = false, string $sequence = null) 插入一条记录并返回自增ID
 * @method integer insertAll(array $dataSet) 插入多条记录
 * @method boolean chunk(integer $count, callable $callback, string $column = null) 分块获取数据
 * @method mixed query(string $sql, array $bind = [], boolean $fetch = false, boolean $master = false, mixed $class = false) SQL查询
 * @method integer execute(string $sql, array $bind = [], boolean $fetch = false, boolean $getLastInsID = false, string $sequence = null) SQL执行
 * @method \think\Paginator paginate(integer $listRows = 15, boolean $simple = false, array $config = []) 分页查询
 * @method mixed transaction(callable $callback) 执行数据库事务
 * @method string getLastSql() 获取最后一条SQL
 */
class Model extends \think\Model
{
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTableName()
    {
        if ($this->table) {
            return $this->table;
        } else {
            return Config::get('database.prefix') . $this->getTableShortName();
        }
    }

    /**
     * 获取去前缀表名
     */
    public function getTableShortName()
    {
        return $this->name;
    }

    /**
     * 软删除
     *
     * @param array $where            
     * @return integer
     */
    public function softDelete($where = [])
    {
        if (empty($where)) {
            $pk = $this->getPk();
            $where[$pk] = $this->getData($pk);
        }
        
        $data = [
            $this->getDeleteTimeField() => time()
        ];
        return $this->where($where)->update($data);
    }

    /**
     * 恢复软删除
     *
     * @param array $where            
     * @return integer
     */
    public function softRecover($where = [])
    {
        if (empty($where)) {
            $pk = $this->getPk();
            $where[$pk] = $this->getData($pk);
        }
        
        $data = [
            $this->getDeleteTimeField() => 0
        ];
        return $this->where($where)->update($data);
    }

    /**
     * 删除字段
     *
     * @return string
     */
    public function getDeleteTimeField()
    {
        return isset($this->deleteTime) ? $this->deleteTime : 'delete_time';
    }
}