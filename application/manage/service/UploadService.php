<?php

namespace app\manage\service;

use core\base\Service;
use core\logic\upload\FileLogic;
use core\logic\upload\StorageLogic;
use core\logic\upload\process\CropProcess;
use newday\upload\Upload;
use newday\upload\core\objects\FileInfo;
use newday\upload\process\OrientationProcess;

class UploadService extends Service
{

    /**
     * 上传文件
     *
     * @param array $uploadFile
     * @return FileInfo|null
     */
    public function upload($uploadFile)
    {
        $this->resetError();
        
        try {
            $file = FileLogic::getSingleton()->upload($uploadFile);
            $storage = StorageLogic::getSingleton()->local();

            $upload = new Upload();
            $upload->setStorage($storage);

            // 图片重力
            $upload->addProcess(new OrientationProcess());

            // 图片大小
            $upload->addProcess(new CropProcess([
                'width' => 1920,
                'height' => 1080
            ]));

            $path = '/{Y}{m}{d}/{ext}/{hash}.{ext}';
            return $upload->upload($file, $path);
        } catch (\Exception $e) {
            $this->setError(self::ERROR_CODE_DEFAULT, $e->getMessage());
            return null;
        }
    }

}