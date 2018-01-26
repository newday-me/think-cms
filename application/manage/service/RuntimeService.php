<?php

namespace app\manage\service;

use think\facade\Env;
use core\base\Service;

class RuntimeService extends Service
{

    /**
     * 清除缓存
     *
     * @param array $paths
     * @param array $option
     */
    public function clearRuntime($paths, $option = [])
    {
        $allowPaths = [
            'cache',
            'log',
            'template',
            'file'
        ];
        $paths = array_intersect($allowPaths, $paths);

        $runtimePath = Env::get('RUNTIME_PATH');
        foreach ($paths as $path) {
            $this->rmDir($runtimePath . $path, $option);
        }
    }

    /**
     * 删除文件夹
     *
     * @param string $path
     * @param array $option
     */
    public function rmDir($path, $option = [])
    {
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                $file = $path . '/' . $file;
                if (is_dir($file)) {
                    $this->rmDir($file, $option);
                } elseif (is_file($file)) {
                    // 修改时间判断
                    $modifyTime = filemtime($file);
                    if (isset($option['start_time']) && $option['start_time'] && $modifyTime < $option['start_time']) {
                        continue;
                    } elseif (isset($option['end_time']) && $option['end_time'] && $modifyTime > $option['end_time']) {
                        continue;
                    }
                    unlink($file);
                }
            }

            // 删除空文件
            if (isset($option['delete_empty']) && $option['delete_empty']) {
                try {
                    rmdir($path);
                } catch (\Exception $e) {
                }
            }
        }
    }
}