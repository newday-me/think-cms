<?php
namespace module\blog\controller;

use think\Request;
use core\blog\model\ArticleTagModel;
use core\blog\model\ArticleTagLinkModel;
use core\blog\logic\ArticleTagLogic;

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
        $model = ArticleTagModel::getInstance()->where($map);
        $this->_page($model);
        
        // 状态下拉
        $this->assignSelectTagStatus();
        
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
     * 删除标签
     *
     * @param Request $request            
     * @return mixed
     */
    public function delete(Request $request)
    {
        // 删除连接
        $map = [
            'tag_id' => $this->_id()
        ];
        ArticleTagLinkModel::getInstance()->where($map)->delete();
        
        $this->_delete(ArticleTagModel::class, false);
    }

    /**
     * 赋值状态下拉
     *
     * @return void
     */
    protected function assignSelectTagStatus()
    {
        $selectTagStatus = ArticleTagLogic::getSingleton()->getSelectStatus();
        $this->assign('select_tag_status', $selectTagStatus);
    }
}