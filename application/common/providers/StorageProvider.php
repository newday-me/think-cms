<?php
namespace app\common\providers;

use think\Config;
use cms\Provider;
use app\common\factories\StorageFactory;

class StorageProvider extends Provider
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(\Pimple\Container $pimple)
    {
        $pimple['storage'] = function ($pimple) {
            $driver = Config::get('upload_driver');
            $option = Config::get('upload_' . $driver);
            return StorageFactory::make($driver, $option);
        };
    }

}