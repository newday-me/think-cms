<?php

function check_env()
{
    $items = array(
        'os' => array(
            '操作系统',
            '不限制',
            '类Unix',
            PHP_OS,
            'success'
        ),
        'php' => array(
            'PHP版本',
            '5.3',
            '5.3+',
            PHP_VERSION,
            'success'
        ),
        'upload' => array(
            '附件上传',
            '不限制',
            '2M+',
            '未知',
            'success'
        ),
        'gd' => array(
            'GD库',
            '2.0',
            '2.0+',
            '未知',
            'success'
        ),
        'disk' => array(
            '磁盘空间',
            '5M',
            '不限制',
            '未知',
            'success'
        )
    );
    
    // PHP环境检测
    if ($items['php'][3] < $items['php'][1]) {
        $items['php'][4] = 'error';
        session('error', true);
    }
    
    // 附件上传检测
    if (@ini_get('file_uploads'))
        $items['upload'][3] = ini_get('upload_max_filesize');
        
        // GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if (empty($tmp['GD Version'])) {
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'error';
        session('error', true);
    } else {
        $items['gd'][3] = $tmp['GD Version'];
    }
    unset($tmp);
    
    // 磁盘空间检测
    if (function_exists('disk_free_space')) {
        $items['disk'][3] = floor(disk_free_space(INSTALL_APP_PATH) / (1024 * 1024)) . 'M';
    }
    
    return $items;
}

/**
 * 目录，文件读写检测
 *
 * @return array 检测数据
 */
function check_dirfile()
{
    $items = array(
        array(
            'dir',
            '可写',
            'success',
            './Uploads/Download'
        ),
        array(
            'dir',
            '可写',
            'success',
            './Uploads/Picture'
        ),
        array(
            'dir',
            '可写',
            'success',
            './Uploads/Editor'
        ),
        array(
            'dir',
            '可写',
            'success',
            './Runtime'
        ),
        array(
            'dir',
            '可写',
            'success',
            './Data'
        ),
        array(
            'dir',
            '可写',
            'success',
            './Application/User/Conf'
        ),
        array(
            'file',
            '可写',
            'success',
            './Application/Common/Conf'
        )
    );
    
    foreach ($items as &$val) {
        $item = INSTALL_APP_PATH . $val[3];
        if ('dir' == $val[0]) {
            if (! is_writable($item)) {
                if (is_dir($items)) {
                    $val[1] = '可读';
                    $val[2] = 'error';
                    session('error', true);
                } else {
                    $val[1] = '不存在';
                    $val[2] = 'error';
                    session('error', true);
                }
            }
        } else {
            if (file_exists($item)) {
                if (! is_writable($item)) {
                    $val[1] = '不可写';
                    $val[2] = 'error';
                    session('error', true);
                }
            } else {
                if (! is_writable(dirname($item))) {
                    $val[1] = '不存在';
                    $val[2] = 'error';
                    session('error', true);
                }
            }
        }
    }
    
    return $items;
}

/**
 * 函数检测
 *
 * @return array 检测数据
 */
function check_func()
{
    $items = [];
    
    // MYSQL
    $mysql = [
        'name' => 'MYSQL',
        'type' => '模块'
    ];
    if (function_exists('mysql_connect')) {
        $mysql['state'] = '支持';
    } else {
        $mysql['state'] = '不支持';
    }
    $items[] = $mysql;
    
    // GD
    $gd = [
        'name' => 'GD',
        'type' => '模块'
    ];
    if (function_exists('gd_info')) {
        $gd['state'] = '支持';
    } else {
        $gd['state'] = '不支持';
    }
    $items[] = $gd;
    
    return $items;
}