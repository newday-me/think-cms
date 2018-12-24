<?php

namespace app\manage\controller;

use core\support\Response;
use app\manage\service\UploadService;

class Upload extends Base
{

    /**
     * 上传文件
     */
    public function upload()
    {
        $source = $this->request->param('source');
        switch ($source) {
            case 'file-input':
                $this->fileInputUpload();
                break;
            case 'um-editor':
                $this->umEditorUpload();
                break;
            case 'file_delete':
                $this->success('删除成功');
                break;
            case 'summer-note':
            default:
                $this->normalUpload();
        }
    }

    /**
     * FileInput上传
     */
    protected function fileInputUpload()
    {
        if (!isset($_FILES['file_data'])) {
            $this->error('上传文件不存在');
        }

        $this->doUpload($_FILES['file_data']);
    }

    /**
     * UmEditor上传
     */
    protected function umEditorUpload()
    {
        if (!isset($_FILES['upfile'])) {
            $data = [
                'state' => '上传文件为空'
            ];
            Response::getInstance()->data(json_encode($data), '', 200);
        }

        $fileInfo = UploadService::getSingleton()->upload($_FILES['upfile']);
        if (is_null($fileInfo)) {
            $data = [
                'state' => UploadService::getSingleton()->getErrorInfo()
            ];
        } else {
            $data = [
                'state' => 'SUCCESS',
                'name' => $fileInfo->getName(),
                'url' => $fileInfo->getUrl(),
                'size' => $fileInfo->getSize()
            ];
        }
        Response::getInstance()->data(json_encode($data), 'json', 200);
    }

    /**
     * 普通上传
     */
    protected function normalUpload()
    {
        if (!isset($_FILES['upload_file'])) {
            $this->error('上传文件为空');
        }

        $this->doUpload($_FILES['upload_file']);
    }

    /**
     * 上传文件
     *
     * @param array $uploadFile
     */
    protected function doUpload($uploadFile)
    {
        $fileInfo = UploadService::getSingleton()->upload($uploadFile);
        if (is_null($fileInfo)) {
            $this->error(UploadService::getSingleton()->getErrorInfo());
        } else {
            $data = [
                'name' => $fileInfo->getName(),
                'url' => $fileInfo->getUrl(),
                'size' => $fileInfo->getSize()
            ];
            $this->success('上传成功', '', $data);
        }
    }

}