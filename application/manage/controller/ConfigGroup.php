<?php
namespace app\manage\controller;

use think\Request;
use core\manage\model\ConfigModel;
use core\manage\model\ConfigGroupModel;
use core\manage\validate\ConfigGroupValidate;

class ConfigGroup extends Base
{

    /**
     * 分组列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '用户群组';
        
        // 记录列表
        $list = ConfigGroupModel::getInstance()->order('group_sort desc')->select();
        $this->_list($list);
        
        return $this->fetch();
    }

    /**
     * 添加分组
     *
     * @param Request $request            
     * @return mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'group_name' => $request->param('group_name'),
                'group_info' => $request->param('group_info', ''),
                'group_sort' => $request->param('group_sort', 0)
            ];
            
            // 验证
            $this->_validate(ConfigGroupValidate::class, $data, 'add');
            
            // 添加
            $this->_add(ConfigGroupModel::class, $data);
        } else {
            $this->siteTitle = '新增分组';
            
            return $this->fetch();
        }
    }

    /**
     * 编辑分组
     *
     * @param Request $request            
     * @return mixed
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'group_name' => $request->param('group_name'),
                'group_info' => $request->param('group_info', ''),
                'group_sort' => $request->param('group_sort', 0)
            ];
            
            // 验证
            $this->_validate(ConfigGroupValidate::class, $data, 'edit');
            
            // 保存
            $this->_edit(ConfigGroupModel::class, $data);
        } else {
            $this->siteTitle = '编辑分组';
            
            // 记录
            $this->_record(ConfigGroupModel::class);
            
            return $this->fetch();
        }
    }

    /**
     * 分组排序
     *
     * @return void
     */
    public function sort()
    {
        $this->_sort(ConfigGroupModel::class, 'group_sort');
    }

    /**
     * 删除群组
     *
     * @return void
     */
    public function delete()
    {
        $model = ConfigModel::getInstance();
        $map = [
            'config_gid' => $this->_id()
        ];
        if ($model->where($map)->find()) {
            $this->error('请先删除该分组下的配置');
        }
        
        $this->_delete(ConfigGroupModel::class, false);
    }
}