<?php
namespace core\manage\model;

use core\Model;

class FileModel extends Model
{

    /**
     * 去前缀表名
     *
     * @var unknown
     */
    protected $name = 'manage_file';

    /**
     * 自动写入时间戳
     *
     * @var unknown
     */
    protected $autoWriteTimestamp = true;

    /**
     * 获取扩展名列表
     *
     * @return array
     */
    public function getExtensionList()
    {
        return $this->field('id as value, file_ext as name')
            ->group('file_ext')
            ->select();
    }
}