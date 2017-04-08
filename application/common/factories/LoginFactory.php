<?php
namespace app\common\factories;

use cms\Factory;
use cms\logins\SessionLogin;
use cms\logins\CacheLogin;
use cms\interfaces\LoginInterface;

class LoginFactory extends Factory
{

    /**
     * Session驱动
     *
     * @var unknown
     */
    const TYPE_SESSION = 'session';

    /**
     * Session驱动
     *
     * @var unknown
     */
    const TYPE_CACHE = 'cache';

    /**
     * 类映射
     *
     * @var unknown
     */
    protected static $maps = [
        self::TYPE_SESSION => SessionLogin::class,
        self::TYPE_CACHE => CacheLogin::class
    ];

    /**
     * 创建登录存储对象
     *
     * @param string $type            
     * @param array $option            
     * @return LoginInterface
     */
    public static function make($type, $option = array())
    {
        if (isset(self::$maps[$type])) {
            $class = self::$maps[$type];
            return $class::getSingleton()->setOption($option);
        } else {
            return null;
        }
    }

}