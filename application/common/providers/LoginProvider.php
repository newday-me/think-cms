<?php
namespace app\common\providers;

use think\Config;
use cms\Provider;
use app\common\factories\LoginFactory;

class LoginProvider extends Provider
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(\Pimple\Container $pimple)
    {
        $pimple['login'] = function ($pimple) {
            $driver = Config::get('login_driver') ? Config::get('login_driver') : LoginFactory::TYPE_SESSION;
            return LoginFactory::make($driver);
        };
    }

}