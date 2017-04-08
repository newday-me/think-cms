<?php
namespace app\common\factories;

use cms\Factory;
use cms\files\LocalFile;
use cms\files\UploadFile;
use cms\files\StreamFile;
use cms\files\RemoteFile;
use cms\interfaces\FileInterface;

class FileFactory extends Factory
{

    /**
     * 本地文件
     *
     * @var unknown
     */
    const TYPE_LOCAL = 'local';

    /**
     * 上传文件
     *
     * @var unknown
     */
    const TYPE_UPLOAD = 'upload';

    /**
     * 流式文件
     *
     * @var unknown
     */
    const TYPE_STREAM = 'stream';

    /**
     * 远程文件
     *
     * @var string
     */
    const TYPE_REMOTE = 'remote';

    /**
     * 类映射
     *
     * @var unknown
     */
    protected static $maps = [
        self::TYPE_LOCAL => LocalFile::class,
        self::TYPE_UPLOAD => UploadFile::class,
        self::TYPE_STREAM => StreamFile::class,
        self::TYPE_REMOTE => RemoteFile::class
    ];

    /**
     * 创建文件对象
     *
     * @param string $type            
     * @param array $option            
     *
     * @return FileInterface
     */
    public static function make($type, $option = array())
    {
        if (isset(self::$maps[$type])) {
            $class = self::$maps[$type];
            return (new $class())->setOption($option);
        } else {
            return null;
        }
    }

}