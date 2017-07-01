<?php
namespace core\manage\model;

use core\Model;

class UserModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_user';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 关联群组
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(UserGroupModel::class, UserGroupLinkModel::getInstance()->getTableShortName(), 'group_id', 'user_id');
    }

    /**
     * 获取用户列表
     *
     * @return array
     */
    public function getUserList()
    {
        $list = $this->select();
        $users = [];
        foreach ($list as $vo) {
            $users[$vo['id']] = [
                'name' => $vo['user_name'] . '(' . $vo['user_nick'] . ')',
                'value' => $vo['id']
            ];
        }
        return $users;
    }
}