<?php

namespace app\manage\service;

use cms\Upload;
use core\base\Service;
use core\logic\upload\FileLogic;
use core\logic\upload\StorageLogic;
use cms\upload\process\OrientationProcess;
use core\logic\upload\process\CropProcess;

class UploadService extends Service
{

    /**
     * 上传文件
     *
     * @param array $uploadFile
     * @return \cms\core\objects\ReturnObject
     */
    public function upload($uploadFile)
    {
        try {
            $file = FileLogic::getSingleton()->upload($uploadFile);
            $storage = StorageLogic::getSingleton()->local();

            $option = [
                'dir' => '/'
            ];
            $upload = new Upload($option);
            $upload->setStorage($storage);

            // 图片重力
            $upload->addProcess(new OrientationProcess());

            // 图片大小
            $upload->addProcess(new CropProcess([
                'width' => 1920,
                'height' => 1080
            ]));

            $info = $upload->upload($file);
            return $this->returnSuccess('上传成功', $info);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

}