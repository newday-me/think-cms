<?php

namespace core\logic\upload\storage;

use Upyun\Upyun;
use Upyun\Config;
use newday\upload\core\base\Storage;
use newday\upload\core\interfaces\FileInterface;

class UpYunStorage extends Storage
{

    /**
     * 又拍云对象
     *
     * @var Upyun
     */
    protected $upYun;

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::exists()
     * @throws
     */
    public function exists($path)
    {
        return $this->getUpYun()->has($path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::url()
     */
    public function url($path)
    {
        return $this->getOption('base_url') . $path;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::save()
     * @throws
     */
    public function save(FileInterface $file, $path, $copy = false)
    {
        $params = [
            'return_url' => $this->getOption('return_url'),
            'notify_url' => $this->getOption('notify_url')
        ];
        return $this->getUpYun()->write($path, $file->getContent(), $params);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::read()
     * @throws
     */
    public function read($path)
    {
        return $this->getUpYun()->read($path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::delete()
     * @throws
     */
    public function delete($path)
    {
        return $this->getUpYun()->delete($path);
    }

    /**
     * 又拍云上传对象
     *
     * @return Upyun
     */
    public function getUpYun()
    {
        if (is_null($this->upYun)) {
            $bucket = $this->getOption('bucket');
            $user = $this->getOption('user');
            $password = $this->getOption('password');
            $apiKey = $this->getOption('api_key');
            $boundarySize = $this->getOption('boundary_size') ? $this->getOption('boundary_size') : '5M';

            $config = new Config($bucket, $user, $password);
            $config->setFormApiKey($apiKey);
            $config->sizeBoundary = $this->translateBytes($boundarySize);

            $this->upYun = new Upyun($config);
        }
        return $this->upYun;
    }

    /**
     * 转换文件大小
     *
     * @param string $size
     *
     * @return integer
     */
    public function translateBytes($size)
    {
        $units = [
            'k' => 1,
            'm' => 2,
            'g' => 3,
            't' => 4,
            'p' => 5
        ];
        $size = strtolower($size);
        $bytes = intval($size);
        foreach ($units as $key => $value) {
            if (strpos($size, $key)) {
                $bytes = $bytes * pow(1024, $value);
                break;
            }
        }
        return $bytes;
    }

}