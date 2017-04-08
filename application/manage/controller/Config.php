<?php
namespace app\manage\controller;

use think\Request;
use core\manage\logic\ConfigLogic;
use core\manage\model\ConfigModel;
use core\manage\validate\ConfigValidate;

class Config extends Base
{

    /**
     * 配置列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '配置管理';
        
        // 查询条件
        $map = [];
        
        // 查询条件-分组
        $group = $request->param('group', '');
        if (! empty($group)) {
            $map['config_group'] = $group;
        }
        $this->assign('group', $group);
        
        // 分页列表
        $model = ConfigModel::getSingleton();
        $model = $model->where($map)->order('config_sort asc');
        $this->_page($model);
        
        // 分组列表
        $this->assignGroupList();
        
        return $this->fetch();
    }

    /**
     * 添加配置
     *
     * @param Request $request            
     * @return string|void
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'config_name' => $request->param('config_name'),
                'config_type' => $request->param('config_type'),
                'config_title' => $request->param('config_title'),
                'config_group' => $request->param('config_group'),
                'config_sort' => $request->param('config_sort', 0),
                'config_extra' => $request->param('config_extra'),
                'config_remark' => $request->param('config_remark')
            ];
            
            // 验证
            $this->_validate(ConfigValidate::class, $data, 'add');
            
            // 添加
            $this->_add(ConfigModel::class, $data);
        } else {
            $this->siteTitle = '新增配置';
            
            // 类型列表
            $this->assignTypeList();
            
            return $this->fetch();
        }
    }

    /**
     * 编辑配置
     *
     * @param Request $request            
     * @return string|void
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'config_name' => $request->param('config_name'),
                'config_type' => $request->param('config_type'),
                'config_title' => $request->param('config_title'),
                'config_group' => $request->param('config_group'),
                'config_sort' => $request->param('config_sort', 0),
                'config_extra' => $request->param('config_extra'),
                'config_remark' => $request->param('config_remark')
            ];
            
            // 验证
            $this->_validate(ConfigValidate::class, $data, 'edit');
            
            // 修改
            $this->_edit(ConfigModel::class, $data);
        } else {
            $this->siteTitle = '编辑配置';
            
            // 记录
            $this->_record(ConfigModel::class);
            
            // 类型列表
            $this->assignTypeList();
            
            return $this->fetch();
        }
    }

    /**
     * 更改配置
     *
     * @return void
     */
    public function modify()
    {
        $fields = [
            'config_sort'
        ];
        $this->_modify(ConfigModel::class, $fields);
    }

    /**
     * 删除配置
     *
     * @return void
     */
    public function delete()
    {
        $this->_delete(ConfigModel::class, false, self::JUMP_REFRESH);
    }

    /**
     * 网站设置
     *
     * @param Request $request            
     * @return string|void
     */
    public function setting(Request $request)
    {
        if ($request->isPost()) {
            $config = $request->param('config/a', []);
            
            // 逐条保存
            $model = ConfigModel::getSingleton();
            foreach ($config as $co => $vo) {
                $map = [
                    'config_name' => $co
                ];
                $data = [
                    'config_value' => is_array($vo) ? json_encode($vo, JSON_UNESCAPED_UNICODE) : $vo
                ];
                $model->save($data, $map);
            }
            
            // 刷新缓存
            ConfigLogic::getSingleton()->refreshConfig();
            
            $this->success('保存成功', self::JUMP_REFRESH);
        } else {
            $this->siteTitle = '网站设置';
            
            // 配置列表
            $logic = ConfigLogic::getSingleton();
            $this->_list($logic->getSetting());
            
            return $this->fetch();
        }
    }

    /**
     * 赋值配置分类
     *
     * @return void
     */
    protected function assignTypeList()
    {
        $model = ConfigModel::getSingleton();
        $typeList = $model->getTypeList();
        $this->assign('type_list', $typeList);
    }

    /**
     * 赋值配置分组
     *
     * @return void
     */
    protected function assignGroupList()
    {
        $model = ConfigModel::getSingleton();
        $groupList = $model->getGroupList();
        $this->assign('group_list', $groupList);
    }
}