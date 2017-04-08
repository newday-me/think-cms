<?php
namespace app\common\providers;

use cms\Upload;
use cms\Provider;
use app\common\hooks\upload\CheckHook;
use app\common\hooks\upload\SuccessHook;

class UploadProvider extends Provider
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(\Pimple\Container $pimple)
    {
        $pimple['upload'] = function ($pimple) {
            $upload = new Upload($pimple['storage']);
            
            // 验证文件是否上传
            $upload->addHook(Upload::HOOK_UPLOAD_CHECK, CheckHook::class);
            
            // 文件上传成功的处理
            $upload->addHook(Upload::HOOK_UPLOAD_SUCCESS, SuccessHook::class);
            
            return $upload;
        };
    }

}