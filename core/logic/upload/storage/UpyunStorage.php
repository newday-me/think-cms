<?php

namespace core\logic\upload\storage;

use Upyun\Upyun;
use Upyun\Config;
use cms\support\Util;
use cms\upload\Storage;
use cms\core\interfaces\FileInterface;

class UpyunStorage extends Storage
{

    /**
     * 又拍云对象
     *
     * @var Upyun
     */
    protected $upyun;

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::exists()
     */
    public function exists($path)
    {
        return $this->upyun()->has($path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::save()
     */
    public function save(FileInterface $file, $path)
    {
        $params = [
            'return_url' => $this->getOption('return_url'),
            'notify_url' => $this->getOption('notify_url')
        ];
        return $this->upyun()->write($path, $file->getContent(), $params);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::read()
     */
    public function read($path)
    {
        return $this->upyun()->read($path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::delete()
     */
    public function delete($path)
    {
        return $this->upyun()->delete($path);
    }

    /**
     * 又拍云上传对象
     *
     * @return Upyun
     */
    protected function upyun()
    {
        if (is_null($this->upyun)) {
            $bucket = $this->getOption('bucket');
            $user = $this->getOption('user');
            $password = $this->getOption('password');
            $apiKey = $this->getOption('api_key');
            $boundarySize = $this->getOption('boundary_size') ? $this->getOption('boundary_size') : '5M';

            $util = Util::getSingleton();
            $config = new Config($bucket, $user, $password);
            $config->setFormApiKey($apiKey);
            $config->sizeBoundary = $util->translateBytes($boundarySize);

            $this->upyun = new Upyun($config);
        }
        return $this->upyun;
    }

}