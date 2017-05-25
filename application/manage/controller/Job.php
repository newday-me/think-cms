<?php
namespace app\manage\controller;

use think\Request;
use core\manage\model\JobModel;
use core\manage\logic\JobLogic;

class Job extends Base
{

    /**
     * 任务列表
     *
     * @param Request $request            
     * @return string
     */
    public function index(Request $request)
    {
        $this->siteTitle = '任务列表';
        
        // 查询条件
        $map = [];
        
        // 查询条件-队列
        $queue = $request->param('queue', '');
        if (! empty($queue)) {
            $map['queue'] = $queue;
        }
        $this->assign('queue', $queue);
        
        // 查询条件-关键词
        $keyword = $request->param('keyword', '');
        if (! empty($keyword)) {
            $map['payload'] = [
                'exp',
                'like \'%' . str_replace([
                    '"',
                    '\\'
                ], [
                    '',
                    '%'
                ], json_encode($keyword)) . '%\''
            ];
        }
        $this->assign('keyword', $keyword);
        
        // 分页列表
        $model = JobModel::getInstance();
        $model = $model->where($map);
        $this->_page($model, null, function (&$list) {
            foreach ($list as &$vo) {
                $vo['reserved_at'] += 60;
                $vo['payload'] = var_export(json_decode($vo['payload'], true), true);
            }
        });
        
        // 任务队列下拉
        $this->assignSelectJobQueue();
        
        // 任务状态下拉
        $this->assignSelectJobStatus();
        
        return $this->fetch();
    }

    /**
     * 更改任务
     *
     * @return void
     */
    public function modify()
    {
        $fields = [
            'attempts',
            'reserved'
        ];
        $this->_modify(JobModel::class, $fields);
    }

    /**
     * 任务延时
     *
     * @param Request $request            
     * @return void
     */
    public function delay(Request $request)
    {
        $time = $request->param('time', 0);
        $data = [
            'reserved' => 0,
            'reserved_at' => time() + $time
        ];
        $this->_edit(JobModel::class, $data, null, self::JUMP_REFRESH);
    }

    /**
     * 删除任务
     *
     * @param Request $request            
     * @return mixed
     */
    public function delete(Request $request)
    {
        $this->_delete(JobModel::class, false);
    }

    /**
     * 赋值任务状态下拉
     *
     * @return void
     */
    protected function assignSelectJobStatus()
    {
        $selectJobStatus = JobLogic::getSingleton()->getSelectStatus();
        $this->assign('select_job_status', $selectJobStatus);
    }

    /**
     * 赋值任务队列下拉
     *
     * @return void
     */
    protected function assignSelectJobQueue()
    {
        $selectJobQueue = JobLogic::getSingleton()->getSelectQueue();
        $this->assign('select_job_queue', $selectJobQueue);
    }
}