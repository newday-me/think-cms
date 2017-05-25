<?php
namespace core\manage\logic;

use core\Logic;
use app\common\App;
use core\manage\model\FileModel;

class FileLogic extends Logic
{

    /**
     * 获取扩展名下拉
     *
     * @return array
     */
    public function getSelectExtension()
    {
        return FileModel::getInstance()->field('file_ext as value, file_ext as name')
            ->group('file_ext')
            ->select();
    }

    /**
     * 删除文件
     *
     * @param integer $id            
     * @return boolean
     */
    public function deleteFile($id)
    {
        $model = FileModel::getInstance();
        $file = $model->get($id);
        if (empty($file)) {
            return true;
        }
        
        // 删除存储的文件
        try {
            $storage = App::getSingleton()->storage;
            $storage->delete($storage->getOption('root') . $file['file_path']);
        } catch (\Exception $e) {}
        
        return $file->delete();
    }
}