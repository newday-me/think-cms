<?php
namespace core\manage\logic;

use think\Url;
use core\Logic;
use core\manage\model\MenuModel;

class MenuLogic extends Logic
{

    /**
     * 获取打开方式下拉
     *
     * @return array
     */
    public function getSelectTarget()
    {
        return [
            [
                'name' => '当前窗口',
                'value' => '_self'
            ],
            [
                'name' => '新窗口',
                'value' => '_blank'
            ]
        ];
    }

    /**
     * 获取链接Build下拉
     *
     * @return array
     */
    public function getSelectBuild()
    {
        return [
            [
                'name' => '需要',
                'value' => 1
            ],
            [
                'name' => '不需要',
                'value' => 0
            ]
        ];
    }

    /**
     * 获取状态下拉
     *
     * @return array
     */
    public function getSelectStatus()
    {
        return [
            [
                'name' => '显示',
                'value' => 1
            ],
            [
                'name' => '隐藏',
                'value' => 0
            ]
        ];
    }

    /**
     * 获取列表下拉
     *
     * @return array
     */
    public function getSelectList()
    {
        $nest = $this->getMenuNest();
        
        $list = [];
        $list[] = [
            'name' => '无',
            'value' => 0
        ];
        foreach ($nest['tree'][0] as $vo) {
            $list[] = [
                'name' => $vo['menu_name'],
                'value' => $vo['id']
            ];
            
            if (isset($nest['tree'][$vo['id']])) {
                foreach ($nest['tree'][$vo['id']] as $ko) {
                    $list[] = [
                        'name' => '-- ' . $ko['menu_name'],
                        'value' => $ko['id']
                    ];
                    
                    if (isset($nest['tree'][$ko['id']])) {
                        foreach ($nest['tree'][$ko['id']] as $mo) {
                            $list[] = [
                                'name' => '-- -- ' . $mo['menu_name'],
                                'value' => $mo['id']
                            ];
                        }
                    }
                }
            }
        }
        return $list;
    }

    /**
     * 获取菜单Nest
     *
     * @param array $map            
     * @return array
     */
    public function getMenuNest($map = [])
    {
        $list = MenuModel::getInstance()->where($map)
            ->cache(2)
            ->order('menu_sort desc')
            ->column('*', 'id');
        
        $tree = [];
        foreach ($list as $vo) {
            $menuPid = $vo['menu_pid'];
            if (! isset($tree[$menuPid])) {
                $tree[$menuPid] = [];
            }
            $tree[$menuPid][] = $vo;
        }
        
        return [
            'list' => $list,
            'tree' => $tree
        ];
    }

    /**
     * 处理菜单数据
     *
     * @param array $data            
     * @return array
     */
    public function processMenuData($data)
    {
        $data['menu_flag'] = $this->parseMenuFlag($data['menu_url'], $data['menu_build']);
        return $data;
    }

    /**
     * 解析菜单标识
     *
     * @param string $link            
     * @param boolean $build            
     * @return string
     */
    public function parseMenuFlag($link, $build = true)
    {
        // 外链
        if ($build == false) {
            return md5($link);
        }
        
        // 测试连接
        $url_test = 'path/test/domain';
        $url_path = str_replace($url_test . '.html', '', Url::build($url_test, '', true, true));
        
        // 相对url
        $url = Url::build($link, '', true, true);
        $url_relative = str_replace([
            $url_path,
            '.html'
        ], '', $url);
        
        // Url标识
        $arr = explode('/', $url_relative);
        if (strpos($link, '@module') !== false) {
            $arr = array_slice($arr, 0, 4);
        } else {
            $arr = array_slice($arr, 0, 3);
        }
        return implode('/', $arr);
    }
}