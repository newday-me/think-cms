<?php
use think\migration\Migrator;

use think\Db;
use think\helper\Str;
use core\manage\logic\UserLogic;
use core\manage\model\UserModel;
use core\manage\model\UserGroupModel;
use core\manage\model\MenuModel;
use core\manage\model\ConfigModel;
use core\manage\model\ConfigGroupModel;
use core\manage\model\UserLoginModel;
use core\manage\model\FileModel;
use core\manage\model\BackupModel;

class InitManageTableData extends Migrator
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
        
        $this->initUserAndGroup();
        
        $this->initConfig();
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
                'menu_name' => '系统',
                'sub_menu' => [
                    [
                        'menu_name' => '网站',
                        'sub_menu' => [
                            [
                                'menu_name' => '控制台',
                                'menu_url' => 'manage/index/index',
                                'menu_flag' => 'manage/index/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '账号设置',
                                        'menu_url' => 'manage/index/account',
                                        'menu_flag' => 'manage/index/account'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '网站设置',
                                'menu_url' => 'manage/config/setting',
                                'menu_flag' => 'manage/config/setting'
                            ],
                            [
                                'menu_name' => '菜单管理',
                                'menu_url' => 'manage/menu/index',
                                'menu_flag' => 'manage/menu/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增菜单',
                                        'menu_url' => 'manage/menu/add',
                                        'menu_flag' => 'manage/menu/add'
                                    ],
                                    [
                                        'menu_name' => '编辑菜单',
                                        'menu_url' => 'manage/menu/edit',
                                        'menu_flag' => 'manage/menu/edit'
                                    ],
                                    [
                                        'menu_name' => '更改菜单',
                                        'menu_url' => 'manage/menu/modify',
                                        'menu_flag' => 'manage/menu/modify'
                                    ],
                                    [
                                        'menu_name' => '菜单排序',
                                        'menu_url' => 'manage/menu/sort',
                                        'menu_flag' => 'manage/menu/sort'
                                    ],
                                    [
                                        'menu_name' => '删除菜单',
                                        'menu_url' => 'manage/menu/delete',
                                        'menu_flag' => 'manage/menu/delete'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '缓存清理',
                                'menu_url' => 'manage/index/runtime',
                                'menu_flag' => 'manage/index/runtime'
                            ]
                        ]
                    ],
                    [
                        'menu_name' => '配置',
                        'sub_menu' => [
                            [
                                'menu_name' => '配置分组',
                                'menu_url' => 'manage/config_group/index',
                                'menu_flag' => 'manage/config_group/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增分组',
                                        'menu_url' => 'manage/config_group/add',
                                        'menu_flag' => 'manage/config_group/add'
                                    ],
                                    [
                                        'menu_name' => '编辑分组',
                                        'menu_url' => 'manage/config_group/edit',
                                        'menu_flag' => 'manage/config_group/edit'
                                    ],
                                    [
                                        'menu_name' => '更改分组',
                                        'menu_url' => 'manage/config_group/modify',
                                        'menu_flag' => 'manage/config_group/modify'
                                    ],
                                    [
                                        'menu_name' => '分组排序',
                                        'menu_url' => 'manage/config_group/sort',
                                        'menu_flag' => 'manage/config_group/sort'
                                    ],
                                    [
                                        'menu_name' => '删除分组',
                                        'menu_url' => 'manage/config_group/delete',
                                        'menu_flag' => 'manage/config_group/delete'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '配置列表',
                                'menu_url' => 'manage/config/index',
                                'menu_flag' => 'manage/config/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增配置',
                                        'menu_url' => 'manage/config/add',
                                        'menu_flag' => 'manage/config/add'
                                    ],
                                    [
                                        'menu_name' => '编辑配置',
                                        'menu_url' => 'manage/config/edit',
                                        'menu_flag' => 'manage/config/edit'
                                    ],
                                    [
                                        'menu_name' => '更改配置',
                                        'menu_url' => 'manage/config/modify',
                                        'menu_flag' => 'manage/config/modify'
                                    ],
                                    [
                                        'menu_name' => '配置排序',
                                        'menu_url' => 'manage/config/sort',
                                        'menu_flag' => 'manage/config/sort'
                                    ],
                                    [
                                        'menu_name' => '删除配置',
                                        'menu_url' => 'manage/config/delete',
                                        'menu_flag' => 'manage/config/delete'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'menu_name' => '用户',
                        'sub_menu' => [
                            [
                                'menu_name' => '用户群组',
                                'menu_url' => 'manage/user_group/index',
                                'menu_flag' => 'manage/user_group/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增群组',
                                        'menu_url' => 'manage/user_group/add',
                                        'menu_flag' => 'manage/user_group/add'
                                    ],
                                    [
                                        'menu_name' => '编辑群组',
                                        'menu_url' => 'manage/user_group/edit',
                                        'menu_flag' => 'manage/user_group/edit'
                                    ],
                                    [
                                        'menu_name' => '群组权限',
                                        'menu_url' => 'manage/user_group/auth',
                                        'menu_flag' => 'manage/user_group/auth'
                                    ],
                                    [
                                        'menu_name' => '更改群组',
                                        'menu_url' => 'manage/user_group/modify',
                                        'menu_flag' => 'manage/user_group/modify'
                                    ],
                                    [
                                        'menu_name' => '删除群组',
                                        'menu_url' => 'manage/user_group/delete',
                                        'menu_flag' => 'manage/user_group/delete'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '用户管理',
                                'menu_url' => 'manage/user/index',
                                'menu_flag' => 'manage/user/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '新增用户',
                                        'menu_url' => 'manage/user/add',
                                        'menu_flag' => 'manage/user/add'
                                    ],
                                    [
                                        'menu_name' => '编辑用户',
                                        'menu_url' => 'manage/user/edit',
                                        'menu_flag' => 'manage/user/edit'
                                    ],
                                    [
                                        'menu_name' => '更改用户',
                                        'menu_url' => 'manage/user/modify',
                                        'menu_flag' => 'manage/user/modify'
                                    ],
                                    [
                                        'menu_name' => '删除用户',
                                        'menu_url' => 'manage/user/delete',
                                        'menu_flag' => 'manage/user/delete'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '登录日志',
                                'menu_url' => 'manage/user_login/index',
                                'menu_flag' => 'manage/user_login/index'
                            ]
                        ]
                    ],
                    [
                        'menu_name' => '队列',
                        'sub_menu' => [
                            [
                                'menu_name' => '任务管理',
                                'menu_url' => 'manage/job/index',
                                'menu_flag' => 'manage/job/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '任务延时',
                                        'menu_url' => 'manage/job/delay',
                                        'menu_flag' => 'manage/job/delay'
                                    ],
                                    [
                                        'menu_name' => '更改任务',
                                        'menu_url' => 'manage/job/modify',
                                        'menu_flag' => 'manage/job/modify'
                                    ],
                                    [
                                        'menu_name' => '删除任务',
                                        'menu_url' => 'manage/job/delete',
                                        'menu_flag' => 'manage/job/delete'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'menu_name' => '文件',
                        'sub_menu' => [
                            [
                                'menu_name' => '上传文件',
                                'menu_url' => 'manage/file/upload',
                                'menu_flag' => 'manage/file/upload',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '普通上传',
                                        'menu_url' => 'manage/upload/upload',
                                        'menu_flag' => 'manage/upload/upload'
                                    ],
                                    [
                                        'menu_name' => '编辑器上传',
                                        'menu_url' => 'manage/upload/editor',
                                        'menu_flag' => 'manage/upload/editor'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '附件管理',
                                'menu_url' => 'manage/file/index',
                                'menu_flag' => 'manage/file/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '删除文件',
                                        'menu_url' => 'manage/file/delete',
                                        'menu_flag' => 'manage/file/delete'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'menu_name' => '数据库',
                        'sub_menu' => [
                            [
                                'menu_name' => '数据备份',
                                'menu_url' => 'manage/backup/index',
                                'menu_flag' => 'manage/backup/index',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '备份数据库',
                                        'menu_url' => 'manage/backup/backup',
                                        'menu_flag' => 'manage/backup/backup'
                                    ],
                                    [
                                        'menu_name' => '优化数据库',
                                        'menu_url' => 'manage/backup/optimize',
                                        'menu_flag' => 'manage/backup/optimize'
                                    ],
                                    [
                                        'menu_name' => '修复数据库',
                                        'menu_url' => 'manage/backup/repair',
                                        'menu_flag' => 'manage/backup/repair'
                                    ]
                                ]
                            ],
                            [
                                'menu_name' => '备份记录',
                                'menu_url' => 'manage/backup/log',
                                'menu_flag' => 'manage/backup/log',
                                'sub_menu' => [
                                    [
                                        'menu_name' => '删除备份',
                                        'menu_url' => 'manage/backup/delete',
                                        'menu_flag' => 'manage/backup/delete'
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

    /**
     * 初始化用户和群组
     */
    protected function initUserAndGroup()
    {
        $menus = MenuModel::getInstance()->field('id')->select();
        $menuIds = [];
        foreach ($menus as $menu) {
            $menuIds[] = $menu['id'];
        }
        
        $data = [
            [
                'group' => [
                    'group_name' => '管理员',
                    'group_info' => '管理网站',
                    'home_page' => 'manage/index/index',
                    'group_menus' => implode(',', $menuIds),
                    'group_status' => 1
                ],
                'users' => [
                    [
                        'user_name' => 'admin',
                        'user_passwd' => UserLogic::getSingleton()->encryptPasswd('123456'),
                        'user_nick' => '管理员',
                        'user_status' => 1
                    ]
                ]
            ]
        ];
        foreach ($data as $vo) {
            $group = UserGroupModel::getInstance()->create($vo['group']);
            
            foreach ($vo['users'] as $ko) {
                $ko['user_gid'] = $group['id'];
                UserModel::getInstance()->create($ko);
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
                    'group_name' => '系统',
                    'group_info' => '系统配置..'
                ],
                'items' => [
                    [
                        'config_name' => 'app_debug',
                        'config_value' => 1,
                        'config_type' => 'radio',
                        'config_title' => '调试模式',
                        'config_extra' => '0:关闭|1:开启'
                    ],
                    [
                        'config_name' => 'app_trace',
                        'config_value' => 0,
                        'config_type' => 'radio',
                        'config_title' => '应用Trace',
                        'config_extra' => '0:关闭|1:开启'
                    ],
                    [
                        'config_name' => 'url_convert',
                        'config_value' => 0,
                        'config_type' => 'radio',
                        'config_title' => '转换URL',
                        'config_extra' => '0:不转换|1:转换'
                    ],
                    [
                        'config_name' => 'url_route_on',
                        'config_value' => 0,
                        'config_type' => 'radio',
                        'config_title' => '开启路由',
                        'config_extra' => '0:关闭|1:开启'
                    ],
                    [
                        'config_name' => 'url_route_must',
                        'config_value' => 0,
                        'config_type' => 'radio',
                        'config_title' => '强制路由',
                        'config_extra' => '0:不强制|1:强制使用'
                    ],
                    [
                        'config_name' => 'url_domain_deploy',
                        'config_value' => 0,
                        'config_type' => 'radio',
                        'config_title' => '域名部署',
                        'config_extra' => '0:否|1:是'
                    ],
                    [
                        'config_name' => 'url_domain_root',
                        'config_value' => '',
                        'config_type' => 'text',
                        'config_title' => '网站域名',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'log',
                        'config_value' => [
                            'type' => 'File',
                            'path' => '{LOG_PATH}',
                            'level' => ''
                        ],
                        'config_type' => 'array',
                        'config_title' => '日志设置',
                        'config_extra' => 'type,path,level'
                    ],
                    [
                        'config_name' => 'trace',
                        'config_value' => [
                            'type' => 'Html'
                        ],
                        'config_type' => 'array',
                        'config_title' => 'Trace设置',
                        'config_extra' => 'type'
                    ],
                    [
                        'config_name' => 'cache',
                        'config_value' => [
                            'type' => 'File',
                            'path' => '{CACHE_PATH}',
                            'prefix' => '',
                            'expire' => 0
                        ],
                        'config_type' => 'array',
                        'config_title' => '缓存设置',
                        'config_extra' => 'type,path,prefix,expire'
                    ],
                    [
                        'config_name' => 'session',
                        'config_value' => [
                            'id' => '',
                            'var_session_id' => '',
                            'prefix' => 'think',
                            'type' => '',
                            'auto_start' => 1
                        ],
                        'config_type' => 'array',
                        'config_title' => '会话设置',
                        'config_extra' => 'id,var_session_id,prefix,type,auto_start'
                    ],
                    [
                        'config_name' => 'cookie',
                        'config_value' => [
                            'prefix' => '',
                            'expire' => 0,
                            'path' => '/',
                            'domain' => '',
                            'secure' => 0,
                            'httponly' => '',
                            'setcookie' => 1
                        ],
                        'config_type' => 'array',
                        'config_title' => 'Cookie设置',
                        'config_extra' => 'prefix,expire,path,domain,secure,httponly,setcookie'
                    ]
                ]
            ],
            [
                'group' => [
                    'group_name' => '网站',
                    'group_info' => '网站配置..'
                ],
                'items' => [
                    [
                        'config_name' => 'site_title',
                        'config_value' => 'NewDayCms - 哩呵后台管理系统',
                        'config_type' => 'text',
                        'config_title' => '网站标题',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'site_version',
                        'config_value' => date('Y-m-d'),
                        'config_type' => 'text',
                        'config_title' => '网站版本',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'site_base',
                        'config_value' => '/',
                        'config_type' => 'text',
                        'config_title' => '网站目录',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'site_keyword',
                        'config_value' => '哩呵,CMS,ThinkPHP,后台,管理系统',
                        'config_type' => 'tag',
                        'config_title' => '网站关键字',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'site_description',
                        'config_value' => 'NewdayCms ，简单的方式管理数据。期待你的参与，共同打造一个功能更强大的通用后台管理系统。',
                        'config_type' => 'textarea',
                        'config_title' => '网站描述',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'login_driver',
                        'config_value' => 'session',
                        'config_type' => 'radio',
                        'config_title' => '登录驱动',
                        'config_extra' => 'session:Session驱动|cache:Cache驱动'
                    ],
                    [
                        'config_name' => 'crypt',
                        'config_value' => [
                            'mode' => 'cbc',
                            'key' => Str::random(16),
                            'iv' => Str::random(16)
                        ],
                        'config_type' => 'array',
                        'config_title' => '加密配置',
                        'config_extra' => 'mode,key,iv'
                    ],
                    [
                        'config_name' => 'bakup_path',
                        'config_value' => '{ROOT_PATH}database/backups',
                        'config_type' => 'text',
                        'config_title' => '备份路径',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'cms_file',
                        'config_value' => 'http://static.newday.me/cms/0.0.3.zip',
                        'config_type' => 'file',
                        'config_title' => 'CMS文件',
                        'config_extra' => ''
                    ]
                ]
            ],
            [
                'group' => [
                    'group_name' => '上传',
                    'group_info' => '上传配置..'
                ],
                'items' => [
                    [
                        'config_name' => 'upload_driver',
                        'config_value' => 'local',
                        'config_type' => 'radio',
                        'config_title' => '上传驱动',
                        'config_extra' => 'local:本地|upyun:又拍云|qiniu:七牛云|ftp:FTP'
                    ],
                    [
                        'config_name' => 'upload_local',
                        'config_value' => [
                            'root' => '{WEB_PATH}',
                            'dir' => 'upload/',
                            'base_url' => 'http://www.domain.com/'
                        ],
                        'config_type' => 'array',
                        'config_title' => '本地上传',
                        'config_extra' => 'root,dir,base_url'
                    ],
                    [
                        'config_name' => 'upload_upyun',
                        'config_value' => [
                            'root' => '/',
                            'dir' => 'upload/',
                            'base_url' => 'http://demo.b0.aicdn.com/',
                            'bucket' => '',
                            'user' => '',
                            'passwd' => '',
                            'api_key' => '',
                            'max_size' => '3M',
                            'block_size' => '1M',
                            'return_url' => '',
                            'notify_url' => ''
                        ],
                        'config_type' => 'array',
                        'config_title' => '又拍云上传',
                        'config_extra' => 'root,dir,base_url,bucket,user,passwd,api_key,max_size,block_size,return_url,notify_url'
                    ],
                    [
                        'config_name' => 'upload_qiniu',
                        'config_value' => [
                            'root' => '/',
                            'dir' => 'upload/',
                            'base_url' => 'http://demo.bkt.clouddn.com/',
                            'bucket' => '',
                            'akey' => '',
                            'skey' => ''
                        ],
                        'config_type' => 'array',
                        'config_title' => '七牛云上传',
                        'config_extra' => 'root,dir,base_url,bucket,akey,skey'
                    ],
                    [
                        'config_name' => 'upload_ftp',
                        'config_value' => [
                            'root' => '/',
                            'dir' => 'upload/',
                            'base_url' => 'http://demo.b0.aicdn.com/',
                            'host' => 'v0.ftp.upyun.com',
                            'ssl' => 0,
                            'port' => 21,
                            'timeout' => 60,
                            'user' => 'user/bucket',
                            'passwd' => 'passwd'
                        ],
                        'config_type' => 'array',
                        'config_title' => 'FTP上传',
                        'config_extra' => 'root,dir,base_url,host,ssl,port,timeout,user,passwd'
                    ]
                ]
            ],
            [
                'group' => [
                    'group_name' => '后台',
                    'group_info' => '后台配置..'
                ],
                'items' => [
                    [
                        'config_name' => 'manage_verify_code',
                        'config_value' => 1,
                        'config_type' => 'radio',
                        'config_title' => '开启验证码',
                        'config_extra' => '0:不开启|1:开启'
                    ],
                    [
                        'config_name' => 'manage_html_minify',
                        'config_value' => 1,
                        'config_type' => 'radio',
                        'config_title' => '压缩HTML',
                        'config_extra' => '0:不压缩|1:压缩'
                    ],
                    [
                        'config_name' => 'manage_rows_num',
                        'config_value' => 10,
                        'config_type' => 'text',
                        'config_title' => '每页条数',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'manage_public_action',
                        'config_value' => [
                            'manage.start.*'
                        ],
                        'config_type' => 'array',
                        'config_title' => '公共行为',
                        'config_extra' => ''
                    ],
                    [
                        'config_name' => 'manage_editor',
                        'config_value' => 'ueditor',
                        'config_type' => 'radio',
                        'config_title' => '编辑器',
                        'config_extra' => 'wang:wangEditor|ueditor:Ueditor'
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
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $tables = [
            UserModel::getInstance()->getTableName(),
            UserGroupModel::getInstance()->getTableName(),
            UserLoginModel::getInstance()->getTableName(),
            MenuModel::getInstance()->getTableName(),
            ConfigModel::getInstance()->getTableName(),
            ConfigModel::getInstance()->getTableName(),
            FileModel::getInstance()->getTableName(),
            BackupModel::getInstance()->getTableName()
        ];
        foreach ($tables as $table) {
            Db::connect()->query('truncate table ' . $table);
        }
    }
}
