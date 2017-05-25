<?php
namespace app\manage\controller;

use think\Request;
use core\manage\logic\UserLogic;
use core\manage\model\UserLoginModel;

class UserLogin extends Base
{

    /**
     * 登录日志
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '登录日志';
        
        $map = [];
        
        // 查询条件-用户
        $uid = $request->param('uid/d', 0);
        $uid && $map['login_uid'] = $uid;
        $this->assign('uid', $uid);
        
        // 查询条件-开始时间
        $start_time = $request->param('start_time', '');
        $this->assign('start_time', $start_time);
        
        // 查询条件-结束时间
        $end_time = $request->param('end_time', '');
        $this->assign('end_time', $end_time);
        
        // 查询条件-时间
        if (! empty($start_time) && ! empty($end_time)) {
            $map['create_time'] = [
                'between',
                [
                    strtotime($start_time),
                    strtotime($end_time)
                ]
            ];
        } elseif (! empty($start_time)) {
            $map['create_time'] = [
                'egt',
                strtotime($start_time)
            ];
        } elseif (! empty($end_time)) {
            $map['create_time'] = [
                'elt',
                strtotime($end_time)
            ];
        }
        
        // 关键词
        $keyword = $request->param('keyword', '');
        if (! empty($keyword)) {
            $map['login_ip|login_agent'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        // 分页列表
        $model = UserLoginModel::getInstance()->with('user')->where($map);
        $this->_page($model, null, function (&$list) {
            foreach ($list as $co => $vo) {
                $vo['user_nick'] = $vo->user ? $vo->user['user_nick'] : '未知';
            }
        });
        
        // 登录用户下拉
        $this->assignSelectLoginUser();
        
        return $this->fetch();
    }

    /**
     * 赋值登录用户下拉
     *
     * @return void
     */
    protected function assignSelectLoginUser()
    {
        $selectLoginUser = UserLogic::getSingleton()->getSelectList();
        $this->assign('select_login_user', $selectLoginUser);
    }
}