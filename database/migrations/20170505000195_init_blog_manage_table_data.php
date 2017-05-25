<?php
use think\migration\Migrator;
use core\manage\model\MenuModel;
use core\manage\model\UserGroupModel;
use core\manage\model\ConfigModel;
use core\manage\model\ConfigGroupModel;

class InitBlogManageTableData extends Migrator
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::up()
     */
    public function up()
    {
        $this->initMenu();
        
        $this->appendGroupMenu();
        
        $this->initConfig();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $model = MenuModel::getInstance();
        
        // 主菜单
        $map = [
            'menu_name' => '博客'
        ];
        $model->where($map)->delete();
        
        // 模块菜单
        $map = [
            'menu_url' => [
                'like',
                '%@module/blog%'
            ]
        ];
        $model->where($map)->delete();
        
        // 配置
        $map = [
            'group_name' => '博客'
        ];
        $configGroup = ConfigModel::where($map)->find();
        
        $map = [
            'config_gid' => $configGroup['id']
        ];
        ConfigModel::getInstance()->where($map)->delete();
        
        $configGroup->delete();
    }

    /**
     * 初始化菜单
     *
     * @return void
     */
    protected function initMenu()
    {
        $data = [
            [
                'menu_name' => '博客',
                'sub_menu' => [
                    [
                        'menu_name' => '文章',
                        'sub_menu' => [
                            [
                                'menu_name' => '文章分类',
                                'menu_url' => '@module/blog/article_cate/index',
                                'menu_flag' => 'module/blog/article_cate/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增分类',
                                        'menu_url' => '@module/blog/article_cate/add',
                                        'menu_flag' => 'module/blog/article_cate/add'
                                    ],
                                    [
                                        'menu_name' => '编辑分类',
                                        'menu_url' => '@module/blog/article_cate/edit',
                                        'menu_flag' => 'module/blog/article_cate/edit'
                                    ],
                                    [
                                        'menu_name' => '更改分类',
                                        'menu_url' => '@module/blog/article_cate/modify',
                                        'menu_flag' => 'module/blog/article_cate/modify'
                                    ],
                                    [
                                        'menu_name' => '删除分类',
                                        'menu_url' => '@module/blog/article_cate/delete',
                                        'menu_flag' => 'module/blog/article_cate/delete'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '文章标签',
                                'menu_url' => '@module/blog/article_tag/index',
                                'menu_flag' => 'module/blog/article_tag/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '更改标签',
                                        'menu_url' => '@module/blog/article_tag/modify',
                                        'menu_flag' => 'module/blog/article_tag/modify'
                                    ],
                                    [
                                        'menu_name' => '删除标签',
                                        'menu_url' => '@module/blog/article_tag/delete',
                                        'menu_flag' => 'module/blog/article_tag/delete'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '文章管理',
                                'menu_url' => '@module/blog/article/index',
                                'menu_flag' => 'module/blog/article/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增文章',
                                        'menu_url' => '@module/blog/article/add',
                                        'menu_flag' => 'module/blog/article/add'
                                    ],
                                    [
                                        'menu_name' => '编辑文章',
                                        'menu_url' => '@module/blog/article/edit',
                                        'menu_flag' => 'module/blog/article/edit'
                                    ],
                                    [
                                        'menu_name' => '更改文章',
                                        'menu_url' => '@module/blog/article/modify',
                                        'menu_flag' => 'module/blog/article/modify'
                                    ],
                                    [
                                        'menu_name' => '删除文章',
                                        'menu_url' => '@module/blog/article/delete',
                                        'menu_flag' => 'module/blog/article/delete'
                                    ],
                                    [
                                        'menu_name' => '恢复文章',
                                        'menu_url' => '@module/blog/article/recover',
                                        'menu_flag' => 'module/blog/article/recover'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '文章回收站',
                                'menu_url' => '@module/blog/article/recycle',
                                'menu_flag' => 'module/blog/article/recycle'
                            ]
                        ]
                    ],
                    [
                        'menu_name' => '页面',
                        'sub_menu' => [
                            [
                                'menu_name' => '页面管理',
                                'menu_url' => '@module/blog/page/index',
                                'menu_flag' => 'module/blog/page/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增页面',
                                        'menu_url' => '@module/blog/page/add',
                                        'menu_flag' => 'module/blog/page/add'
                                    ],
                                    [
                                        'menu_name' => '编辑页面',
                                        'menu_url' => '@module/blog/page/edit',
                                        'menu_flag' => 'module/blog/page/edit'
                                    ],
                                    [
                                        'menu_name' => '更改页面',
                                        'menu_url' => '@module/blog/page/modify',
                                        'menu_flag' => 'module/blog/page/modify'
                                    ],
                                    [
                                        'menu_name' => '删除页面',
                                        'menu_url' => '@module/blog/page/delete',
                                        'menu_flag' => 'module/blog/page/delete'
                                    ],
                                    [
                                        'menu_name' => '恢复页面',
                                        'menu_url' => '@module/blog/page/recover',
                                        'menu_flag' => 'module/blog/page/recover'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '页面回收站',
                                'menu_url' => '@module/blog/page/recycle',
                                'menu_flag' => 'module/blog/page/recycle',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '恢复页面',
                                        'menu_url' => '@module/blog/page/recover',
                                        'menu_flag' => 'module/blog/page/recover'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        foreach ($data as $vo) {
            
            // 主菜单
            $mainMenu = $this->createMenu($vo);
            if (isset($vo['sub_menu'])) {
                foreach ($vo['sub_menu'] as $ko) {
                    
                    // 二级菜单
                    $subMenu = $this->createMenu($ko, $mainMenu);
                    if (isset($ko['sub_menu'])) {
                        foreach ($ko['sub_menu'] as $mo) {
                            
                            // 三级菜单
                            $menu = $this->createMenu($mo, $subMenu);
                            if (isset($mo['sub_menu'])) {
                                foreach ($mo['sub_menu'] as $no) {
                                    // 四级菜单
                                    $this->createMenu($no, $menu);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 初始化配置
     *
     * @return void
     */
    protected function initConfig()
    {
        $data = [
            [
                'group' => [
                    'group_name' => '博客',
                    'group_info' => '博客配置..'
                ],
                'items' => [
                    [
                        'config_name' => 'blog_title',
                        'config_value' => '哩呵博客',
                        'config_type' => 'text',
                        'config_title' => '博客名称',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'blog_head',
                        'config_value' => 'http://static.newday.me/cms/head.jpg',
                        'config_type' => 'image',
                        'config_title' => '博客头像',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'blog_head_background',
                        'config_value' => 'http://static.newday.me/cms/head_background.jpg',
                        'config_type' => 'image',
                        'config_title' => '头像背景',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'blog_desc',
                        'config_value' => '希望死后的墓志铭可以有底气刻上</br>一生努力，一生被爱<br/>想要的都拥有，得不到的都释怀',
                        'config_type' => 'textarea',
                        'config_title' => '博客说明',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'blog_404_background',
                        'config_value' => 'http://static.newday.me/cms/404.jpg',
                        'config_type' => 'image',
                        'config_title' => '错误页背景',
                        'config_extra' => ''
                    ]
                ]
            ]
        ];
        foreach ($data as $vo) {
            $group = ConfigGroupModel::getInstance()->create($vo['group']);
            
            foreach ($vo['items'] as $ko) {
                $ko['config_gid'] = $group['id'];
                
                if (is_array($ko['config_value'])) {
                    $ko['config_value'] = json_encode($ko['config_value'], JSON_UNESCAPED_UNICODE);
                }
                
                ConfigModel::getInstance()->create($ko);
            }
        }
    }

    /**
     * 增加管理员菜单
     *
     * @return void
     */
    protected function appendGroupMenu()
    {
        $menus = MenuModel::getInstance()->field('id')->column('id', 'id');
        $map = [
            'id' => 1
        ];
        $data = [
            'group_menus' => implode(',', $menus)
        ];
        UserGroupModel::getInstance()->update($data, $map);
    }

    /**
     * 创建菜单
     *
     * @param mixed $menu            
     * @param mixed $parentMneu            
     *
     * @return array
     */
    protected function createMenu($menu, $parentMneu = null)
    {
        if (isset($menu['sub_menu'])) {
            unset($menu['sub_menu']);
        }
        
        $parentMneu && $menu['menu_pid'] = $parentMneu['id'];
        return MenuModel::getInstance()->create($menu);
    }
}
