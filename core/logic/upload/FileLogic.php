<?php

namespace core\logic\upload;

use core\base\Logic;
use cms\upload\file\LocalFile;
use cms\upload\file\StreamFile;
use cms\upload\file\RemoteFile;
use cms\upload\file\UploadFile;

class FileLogic extends Logic
{

    /**
     * 本地文件
     *
     * @param string $path
     * @return LocalFile
     */
    public function local($path)
    {
        return LocalFile::from($path);
    }

    /**
     * 流文件
     *
     * @param string $stream
     * @return StreamFile
     */
    public function stream($stream)
    {
        return StreamFile::from($stream);
    }

    /**
     * 远程文件
     *
     * @param string $url
     * @return RemoteFile
     */
    public function remote($url)
    {
        return RemoteFile::from($url);
    }

    /**
     * 上传文件
     *
     * @param $file
     * @return UploadFile
     */
    public function upload($file)
    {
        return UploadFile::from($file);
    }

}