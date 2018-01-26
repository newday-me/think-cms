<?php

namespace app\manage\controller;

use app\manage\logic\MenuLogic;
use think\facade\Url;
use app\manage\service\UserGroupService;

class UserGroup extends Base
{

    /**
     * 群组管理
     *
     * @return string
     */
    public function index()
    {
        $this->assign('site_title', '群组管理');

        $groupTree = UserGroupService::getSingleton()->getGroupTree();
        $this->assign('group_tree_json', json_encode($groupTree));

        // 操作
        $actionList = [
            'add' => Url::build('add'),
            'edit' => Url::build('edit'),
            'drag' => Url::build('drag'),
            'delete' => Url::build('delete')
        ];
        $this->assign('action_list_json', json_encode($actionList));

        return $this->fetch();
    }

    /**
     *  群组权限
     *
     * @return string
     */
    public function auth()
    {
        $groupNo = $this->request->param('data_no');
        if (empty($groupNo)) {
            $this->error('群组编号为空');
        }

        if ($this->request->isPost()) {
            $menuNos = $this->request->param('menu_nos');
            if ($menuNos) {
                $menuNos = explode(',', $menuNos);
            } else {
                $menuNos = [];
            }

            $return = UserGroupService::getSingleton()->saveGroupAuth($groupNo, $menuNos);
            $this->response($return);
        } else {
            $this->assign('site_title', '群组权限');

            // 菜单树
            $return = UserGroupService::getSingleton()->getGroupMenuTree($groupNo);
            if (!$return->isSuccess()) {
                $this->error($return->getMsg());
            }
            $this->assign('menu_tree_json', json_encode($return->getData()));

            // 操作
            $actionList = [
                'auth' => Url::build('auth', ['data_no' => $groupNo])
            ];
            $this->assign('action_list_json', json_encode($actionList));

            return $this->fetch();
        }
    }

    /**
     * 创建群组
     */
    public function add()
    {
        $data = [
            'group_pno' => $this->request->param('group_pno'),
            'group_name' => $this->request->param('group_name'),
            'group_info' => $this->request->param('group_info', '')
        ];
        $return = UserGroupService::getSingleton()->createGroup($data);
        $this->response($return);
    }

    /**
     * 编辑群组
     */
    public function edit()
    {
        $groupNo = $this->request->param('data_no');
        if (empty($groupNo)) {
            $this->error('群组编号为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $return = UserGroupService::getSingleton()->getGroup($groupNo);
                $this->response($return);
                break;
            case 'save':
                $data = [
                    'group_pno' => $this->request->param('group_pno'),
                    'group_name' => $this->request->param('group_name'),
                    'group_info' => $this->request->param('group_info', '')
                ];
                $return = UserGroupService::getSingleton()->updateGroup($groupNo, $data);
                $this->response($return);
                break;
            default:
                $this->error('未知操作');
        }
    }

    /**
     * 拖动群组
     */
    public function drag()
    {
        $mode = $this->request->param('mode');
        $fromGroupNo = $this->request->param('from_group_no');
        $toGroupNo = $this->request->param('to_group_no');
        if (empty($fromGroupNo) || empty($toGroupNo)) {
            $this->error('数据不完整');
        }

        $return = UserGroupService::getSingleton()->dragGroup($mode, $fromGroupNo, $toGroupNo);
        $this->response($return);
    }

    /**
     * 删除群组
     */
    public function delete()
    {
        $groupNo = $this->request->param('data_no');
        if (empty($groupNo)) {
            $this->error('群组编号为空');
        }

        $return = UserGroupService::getSingleton()->deleteGroup($groupNo);
        $this->response($return);
    }

}