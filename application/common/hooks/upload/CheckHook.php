<?php
namespace app\common\hooks\upload;

use core\manage\model\FileModel;

class CheckHook
{

    /**
     * 文件上传前验证
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
        if ($file) {
            $params['url'] = $file['file_url'];
            $params['path'] = $file['file_path'];
        }
        return true;
    }

}