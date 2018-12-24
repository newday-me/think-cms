<?php

namespace core\db\manage\data;

use core\base\Data;
use think\facade\Request;
use core\db\manage\model\ManageUserLoginModel;

class ManageUserLoginData extends Data
{

    /**
     * 新增日志
     *
     * @param string $userNo
     * @return array|null
     */
    public function addLog($userNo)
    {
        $data = [
            'user_no' => $userNo,
            'login_ip' => Request::ip(),
            'login_agent' => Request::header('user-agent')
        ];
        $record = ManageUserLoginModel::create($data);
        return $this->transToArray($record);
    }

    /**
     * 获取分页的日志列表
     *
     * @param \Closure $closure
     * @param int $page
     * @param int $pageSize
     * @return array
     * @throws
     */
    public function getLogListPage($closure, $page, $pageSize)
    {
        $list = ManageUserLoginModel::getInstance()->with('user')->where($closure)->page($page, $pageSize)->select();
        return $this->transToArray($list);
    }

    /**
     * 获取日志记录数
     *
     * @param \Closure $closure
     * @return int
     */
    public function getLogListCount($closure)
    {
        return ManageUserLoginModel::getInstance()->where($closure)->count();
    }

}