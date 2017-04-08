<?php
namespace app\common;

use cms\Container;
use cms\traits\InstanceTrait;
use app\common\providers\LoginProvider;
use app\common\providers\StorageProvider;
use app\common\providers\UploadProvider;

/**
 * App
 *
 * @property \cms\Login $login 登录
 * @property \cms\Storage $storage 上传
 * @property \cms\Upload $upload 上传
 */
class App extends Container
{
    
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 供应商
     *
     * @var array
     */
    protected $providers = [
        LoginProvider::class,
        StorageProvider::class,
        UploadProvider::class
    ];
}