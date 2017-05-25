<?php
namespace app\manage\controller;

use cms\Common;
use think\Request;
use core\manage\logic\FileLogic;
use core\manage\model\FileModel;

class File extends Base
{

    /**
     * 文件列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '附件管理';
        
        // 查询条件
        $map = [];
        
        // 查询条件-扩展名
        $extension = $request->param('extension', '');
        if (! empty($extension)) {
            $map['file_ext'] = $extension;
        }
        $this->assign('extension', $extension);
        
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
        
        // 查询条件-关键词
        $keyword = $request->param('keyword', '');
        if (! empty($keyword)) {
            $map['file_hash'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        // 分页列表
        $model = FileModel::getInstance()->where($map)->order('id desc');
        $this->_page($model, null, function (&$list) {
            $common = Common::getSingleton();
            foreach ($list as &$vo) {
                $vo['file_size'] = $common->formatBytes($vo['file_size']);
            }
        });
        
        // 文件扩展名下拉
        $this->assignSelectFileExtension();
        
        return $this->fetch();
    }

    /**
     * 删除文件
     *
     * @param Request $request            
     * @return void
     */
    public function delete(Request $request)
    {
        $success = FileLogic::getSingleton()->deleteFile($this->_id());
        if ($success) {
            $this->success('删除文件成功', self::JUMP_REFRESH);
        } else {
            $this->error('删除文件失败');
        }
    }

    /**
     * 上传文件
     *
     * @return string
     */
    public function upload()
    {
        $this->siteTitle = '文件上传';
        return $this->fetch();
    }

    /**
     * 赋值文件扩展名下拉
     *
     * @return void
     */
    protected function assignSelectFileExtension()
    {
        $selectFileExtension = FileLogic::getSingleton()->getSelectExtension();
        $this->assign('select_file_extension', $selectFileExtension);
    }
}