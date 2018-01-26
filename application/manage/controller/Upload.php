<?php

namespace app\manage\controller;

use cms\facade\Response;
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

        $uploadFile = $_FILES['file_data'];
        $this->response($this->doUpload($uploadFile));
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
            Response::data(json_encode($data), '', 200);
        }

        $uploadFile = $_FILES['upfile'];
        $return = $this->doUpload($uploadFile);
        if ($return->isSuccess()) {
            $data = $return->getData();
            $data['state'] = 'SUCCESS';
            Response::data(json_encode($data), '', 200);
        } else {
            $data = [
                'state' => $return->getMsg()
            ];
            Response::data(json_encode($data), '', 200);
        }
    }

    /**
     * 普通上传
     */
    protected function normalUpload()
    {
        if (!isset($_FILES['upload_file'])) {
            $this->error('上传文件为空');
        }

        $uploadFile = $_FILES['upload_file'];
        $this->response($this->doUpload($uploadFile));
    }

    /**
     * 上传文件
     *
     * @param array $uploadFile
     * @return \cms\core\objects\ReturnObject
     */
    protected function doUpload($uploadFile)
    {
        return UploadService::getSingleton()->upload($uploadFile);
    }

}