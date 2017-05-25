<?php
namespace app\common\jobs;

use think\queue\Job;

class EchoJob
{

    /**
     * 执行任务
     *
     * @param Job $job            
     * @param mixed $data            
     * @return void
     */
    public function fire(Job $job, $data)
    {
        if (is_array($data)) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            echo serialize($data);
        }
        
        // 删除任务
        $job->delete();
    }
}
