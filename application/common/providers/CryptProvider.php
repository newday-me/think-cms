<?php
namespace app\common\providers;

use think\Config;
use cms\Crypt;
use cms\Provider;

class CryptProvider extends Provider
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(\Pimple\Container $pimple)
    {
        $pimple['crypt'] = function ($pimple) {
            return new Crypt(Config::get('crypt'));
        };
    }
}