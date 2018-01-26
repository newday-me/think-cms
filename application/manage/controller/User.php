<?php

namespace app\manage\controller;

use think\facade\Url;
use core\db\manage\constant\ManageUserConstant;
use app\manage\logic\WidgetLogic;
use app\manage\service\UserService;

class User extends Base
{

    /**
     * 用户管理
     *
     * @return string
     */
    public function index()
    {
        $this->assign('site_title', '用户管理');
        $widget = WidgetLogic::getSingleton()->getWidget();

        // 状态
        $userStatus = $this->request->param('user_status');
        if (!is_null($userStatus)) {
            $userStatus = intval($userStatus);
        }
        $userStatusHtml = $widget->search('select', [
            'title' => '状态',
            'name' => 'user_status',
            'value' => $userStatus,
            'list' => [
                [
                    'name' => '不限',
                    'value' => ''
                ],
                [
                    'name' => '启用',
                    'value' => ManageUserConstant::STATUS_ENABLE
                ],
                [
                    'name' => '禁用',
                    'value' => ManageUserConstant::STATUS_DISABLE
                ]
            ]
        ]);
        $this->assign('user_status_html', $userStatusHtml);

        // 关键字
        $keyword = $this->request->param('keyword');
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
            'holder' => '关键字...'
        ]);
        $this->assign('keyword_html', $keywordHtml);

        $nowPage = $this->request->param('page', 1);
        list($list, $page) = UserService::getSingleton()->getUserListPage($userStatus, $keyword, $nowPage, 10);
        $this->assign('list', $list);
        $this->assign('page', $page);

        // 操作
        $actionList = [
            'search' => Url::build('index'),
            'add' => Url::build('add'),
            'edit' => Url::build('edit'),
            'auth' => Url::build('auth'),
            'delete' => Url::build('delete')
        ];
        $this->assign('action_list', $actionList);
        $this->assign('action_list_json', json_encode($actionList));

        return $this->fetch();
    }

    /**
     * 账号设置
     */
    public function account()
    {
        $data = [
            'user_nick' => $this->request->param('user_nick'),
            'user_password' => $this->request->param('user_password')
        ];

        $userPasswordConfirm = $this->request->param('user_password_confirm');
        if ($userPasswordConfirm != $data['user_password']) {
            $this->error('两次输入的密码不一致');
        }

        $return = UserService::getSingleton()->updateAccount($data);
        $this->response($return);
    }

    /**
     * 添加用户
     */
    public function add()
    {
        $data = [
            'user_name' => $this->request->param('user_name'),
            'user_nick' => $this->request->param('user_nick'),
            'user_password' => $this->request->param('user_password'),
            'user_status' => $this->request->param('user_status')
        ];

        $return = UserService::getSingleton()->createUser($data);
        $this->response($return);
    }

    /**
     * 编辑用户
     */
    public function edit()
    {
        $userNo = $this->request->param('data_no');
        if (empty($userNo)) {
            $this->error('用户编号为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $return = UserService::getSingleton()->getUser($userNo);
                $this->response($return);
                break;
            case 'save':
                $data = [
                    'user_name' => $this->request->param('user_name'),
                    'user_nick' => $this->request->param('user_nick'),
                    'user_password' => $this->request->param('user_password'),
                    'user_status' => $this->request->param('user_status')
                ];
                $return = UserService::getSingleton()->updateUser($userNo, $data);
                $this->response($return);
                break;
            default:
                $this->error('未知操作');
        }
    }

    /**
     * 用户群组
     */
    public function auth()
    {
        $userNo = $this->request->param('data_no');
        if (empty($userNo)) {
            $this->error('用户编号为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $groupTree = UserService::getSingleton()->getUserGroupTree($userNo);
                $this->success('获取成功', '', $groupTree);
                break;
            case 'save':
                $groupNos = $this->request->param('group_nos');
                if ($groupNos) {
                    $groupNos = explode(',', $groupNos);
                } else {
                    $groupNos = [];
                }

                UserService::getSingleton()->saveUserAuth($userNo, $groupNos);
                $this->success('保存成功');
                break;
            default:
                $this->error('未知操作');
        }
    }

    /**
     * 更改用户
     */
    public function modify()
    {
        $userNo = $this->request->param('data_no');
        if (empty($userNo)) {
            $this->error('用户编号为空');
        }

        $field = $this->request->param('field');
        if (empty($field)) {
            $this->error('字段名为空');
        }

        $value = $this->request->param('value');
        if (is_null($value)) {
            $this->error('值为空');
        }

        $return = UserService::getSingleton()->modifyUser($userNo, $field, $value);
        $this->response($return);
    }

    /**
     * 删除用户
     */
    public function delete()
    {
        $userNo = $this->request->param('data_no');
        if (empty($userNo)) {
            $this->error('用户编号为空');
        }

        $return = UserService::getSingleton()->deleteUser($userNo);
        $this->response($return);
    }

}