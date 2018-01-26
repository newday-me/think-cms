<?php

namespace core\logic\upload;

use think\facade\Env;
use core\base\Logic;
use cms\upload\storage\LocalStorage;
use core\logic\upload\storage\UpyunStorage;

class StorageLogic extends Logic
{

    /**
     * 本地存贮
     *
     * @var LocalStorage
     */
    protected $localStorage;

    /**
     * 又拍云存贮
     *
     * @var UpyunStorage
     */
    protected $upyunStorage;

    /**
     * 本地存储
     *
     * @return LocalStorage
     */
    public function local()
    {
        if (is_null($this->localStorage)) {
            $option = [
                'base_url' => Env::get('LOCAL_BASE_URL'),
                'base_path' => Env::get('ROOT_PATH') . '/public' . Env::get('LOCAL_DIR')
            ];
            $this->localStorage = new LocalStorage($option);
        }
        return $this->localStorage;
    }

    /**
     * 又拍云存贮
     *
     * @return UpyunStorage
     */
    public function upyun()
    {
        if (is_null($this->upyunStorage)) {
            $option = [
                'bucket' => '',
                'user' => '',
                'password' => '',
                'api_key' => '',
                'boundary_size' => '5M',
                'return_url' => '',
                'notify_url' => ''
            ];
            $this->upyunStorage = new UpyunStorage($option);
        }
        return $this->upyunStorage;
    }

}