<?php

namespace core\logic\upload\process;

use think\Image;
use cms\upload\Process;
use cms\core\interfaces\FileInterface;
use cms\core\interfaces\FileProcessInterface;

class CropProcess extends Process
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileProcessInterface::process()
     */
    public function process(FileInterface $file)
    {
        // Gd扩展
        if (!extension_loaded('gd')) {
            return true;
        }

        // 文件后缀
        $extensions = [
            'jpg',
            'jpeg',
            'png',
            'gif'
        ];
        if (!in_array($file->getExtension(), $extensions)) {
            return true;
        }

        // 裁剪图片
        $image = Image::open($file->getPath());
        $width = $this->getOption('width') ? $this->getOption('width') : $image->width();
        $height = $this->getOption('height') ? $this->getOption('height') : $image->height();
        $image->thumb($width, $height)->save($file->getPath());

        return true;
    }

}