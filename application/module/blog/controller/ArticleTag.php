<?php
namespace module\blog\controller;

use think\Request;
use core\blog\model\ArticleTagModel;

class ArticleTag extends Base
{

    /**
     * 标签列表
     *
     * @param Request $request            
     *
     * @return void
     */
    public function index(Request $request)
    {
        $this->siteTitle = '标签列表';
        
        // 查询条件
        $map = [];
        
        // 查询条件-状态
        $status = $request->param('status', '');
        if ($status != '') {
            $status = intval($status);
            $map['tag_status'] = $status;
        }
        $this->assign('status', $status);
        
        // 查询条件-关键词
        $keyword = $request->param('keyword');
        if ($keyword != '') {
            $map['tag_name'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        // 列表
        $model = ArticleTagModel::getInstance();
        $model = $model->where($map);
        $this->_page($model);
        
        // 状态列表
        $this->assignStatusList();
        
        return $this->fetch();
    }

    /**
     * 更改标签
     *
     * @return void
     */
    public function modify()
    {
        $fields = [
            'tag_status'
        ];
        $this->_modify(ArticleTagModel::class, $fields);
    }

    /**
     * 赋值状态列表
     *
     * @return void
     */
    protected function assignStatusList()
    {
        $model = ArticleTagModel::getInstance();
        $statusList = $model->getStatusList();
        $this->assign('status_list', $statusList);
    }

}