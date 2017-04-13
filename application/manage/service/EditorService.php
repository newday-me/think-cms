<?php
namespace app\manage\service;

use cms\Service;
use core\manage\model\FileModel;

class EditorService extends Service
{

    /**
     * 图片
     *
     * @var string
     */
    const TYPE_IMAGE = 'image';

    /**
     * 文件
     *
     * @var string
     */
    const TYPE_FILE = 'file';

    /**
     * 列出文件
     *
     * @param string $type            
     * @param number $start            
     * @param number $size            
     * @return array
     */
    public function listFile($type = self::TYPE_FILE, $start = 0, $size = 20)
    {
        $model = FileModel::getInstance();
        $map = [];
        if ($type == self::TYPE_IMAGE) {
            $map['file_ext'] = [
                'in',
                [
                    'png',
                    'jpg',
                    'jpeg',
                    'gif',
                    'bmp'
                ]
            ];
        }
        
        $page = ceil($start / $size);
        $list = $model->where($map)
            ->order('id desc')
            ->paginate($size, false, [
            'page' => $page
        ]);
        
        $images = [];
        foreach ($list as $vo) {
            $images[] = [
                'url' => $vo['file_url']
            ];
        }
        
        return [
            'state' => 'SUCCESS',
            'list' => $images,
            'start' => $start,
            'total' => $list->total()
        ];
    }

    /**
     * 百度编辑器配置
     *
     * @return array
     */
    public function getUeditorConfig()
    {
        return require (APP_PATH . 'common/extra/ueditor.php');
    }

}