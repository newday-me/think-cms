<?php
namespace app\manage\service;

use cms\Service;

class RuntimeService extends Service
{

    /**
     * 清除缓存
     *
     * @param array $paths            
     * @param array $option            
     * @return void
     */
    public function clearRuntime($paths, $option = [])
    {
        $allowPaths = [
            'cache',
            'log',
            'temp'
        ];
        $paths = array_intersect($allowPaths, $paths);
        foreach ($paths as $path) {
            $this->rmDir(RUNTIME_PATH . $path, $option);
        }
    }

    /**
     * 删除文件夹
     *
     * @param string $path            
     * @param array $option            
     * @return boolean
     */
    public function rmDir($path, $option = [])
    {
        $success = false;
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                
                $file = $path . DS . $file;
                if (is_dir($file)) {
                    $this->rmDir($file, $option);
                } elseif (is_file($file)) {
                    // 修改时间判断
                    $mtime = filemtime($file);
                    if (isset($option['start_time']) && $option['start_time'] && $mtime < $option['start_time']) {
                        continue;
                    } elseif (isset($option['end_time']) && $option['end_time'] && $mtime > $option['end_time']) {
                        continue;
                    }
                    unlink($file);
                }
            }
            
            // 删除空文件
            if (isset($option['delete_empty']) && $option['delete_empty']) {
                try {
                    rmdir($path);
                } catch (\Exception $e) {}
            }
            
            $success = true;
        }
        return $success;
    }
}