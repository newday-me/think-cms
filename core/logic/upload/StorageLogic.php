<?php

namespace core\logic\upload;

use think\facade\Env;
use core\base\Logic;
use newday\upload\storage\LocalStorage;
use core\logic\upload\storage\UpYunStorage;

class StorageLogic extends Logic
{

    /**
     * 本地存储
     *
     * @var LocalStorage
     */
    protected $localStorage;

    /**
     * 又拍云存储
     *
     * @var UpYunStorage
     */
    protected $upYunStorage;

    public function local()
    {
        if (is_null($this->localStorage)) {
            $option = [
                'base_url' => Env::get('LOCAL_BASE_URL'),
                'base_path' => Env::get('ROOT_PATH') . Env::get('LOCAL_BASE_PATH')
            ];
            $this->localStorage = new LocalStorage($option);
        }
        return $this->localStorage;
    }

    /**
     * 缓存存储
     *
     * @return UpYunStorage
     */
    public function upYun()
    {
        if (is_null($this->upYunStorage)) {
            $option = [
                'bucket' => Env::get('UPYUN_BUCKET'),
                'user' => Env::get('UPYUN_USER'),
                'password' => Env::get('UPYUN_PASSWORD'),
                'api_key' => Env::get('UPYUN_API_KEY'),
                'boundary_size' => Env::get('UPYUN_BOUNDARY_SIZE'),
                'return_url' => Env::get('UPYUN_RETURN_URL'),
                'notify_url' => Env::get('UPYUN_NOTIFY_URL'),
                'base_url' => Env::get('UPYUN_BASE_URL'),
                'base_path' => Env::get('UPYUN_BASE_PATH')
            ];
            $this->upYunStorage = new UpYunStorage($option);
        }
        return $this->upYunStorage;
    }

}