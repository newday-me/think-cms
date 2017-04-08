<?php
namespace app\common\factories;

use cms\Factory;
use cms\storages\FtpStorage;
use cms\storages\LocalStorage;
use cms\storages\UpyunStorage;
use cms\storages\QiniuStorage;

class StorageFactory extends Factory
{

    /**
     * 本地
     *
     * @var unknown
     */
    const TYPE_LOCAL = 'local';

    /**
     * FTP
     *
     * @var unknown
     */
    const TYPE_FTP = 'ftp';

    /**
     * 又拍云
     *
     * @var string
     */
    const TYPE_UPYUN = 'upyun';

    /**
     * 七牛云
     *
     * @var string
     */
    const TYPE_QINIU = 'qiniu';

    /**
     * 类映射
     *
     * @var unknown
     */
    protected static $maps = [
        self::TYPE_LOCAL => LocalStorage::class,
        self::TYPE_FTP => FtpStorage::class,
        self::TYPE_UPYUN => UpyunStorage::class,
        self::TYPE_QINIU => QiniuStorage::class
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