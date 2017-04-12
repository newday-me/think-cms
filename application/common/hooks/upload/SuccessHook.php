<?php
namespace app\common\hooks\upload;

use core\manage\model\FileModel;

class SuccessHook
{

    /**
     * 文件上传成功
     *
     * @param array $params            
     *
     * @return boolean
     */
    public static function hook(&$params)
    {
        $model = FileModel::getInstance();
        $map = [
            'file_hash' => $params['hash']
        ];
        $file = $model->where($map)->find();
        if (empty($file)) {
            $data = [
                'file_hash' => $params['hash'],
                'file_ext' => $params['ext'],
                'file_size' => $params['size'],
                'file_path' => $params['path'],
                'file_url' => $params['url']
            ];
            $model->create($data);
        }
        return true;
    }

}