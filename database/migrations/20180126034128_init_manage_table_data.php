<?php

use think\Db;
use think\migration\Migrator;
use core\db\manage\data\ManageUserData;
use core\db\manage\model\ManageUserModel;
use core\db\manage\model\ManageUserLoginModel;
use core\db\manage\constant\ManageUserConstant;
use core\db\manage\constant\ManageMenuConstant;
use core\db\manage\model\ManageUserGroupModel;
use core\db\manage\model\ManageUserGroupLinkModel;
use core\db\manage\model\ManageMenuModel;
use core\db\manage\model\ManageMenuLinkModel;

class InitManageTableData extends Migrator
{

    public function up()
    {
        $this->initUser();
        $this->initUserGroup();
        $this->initUserGroupLink();
        $this->initMenu();
        $this->initMenuLink();
    }

    public function down()
    {
        $tables = [
            ManageUserModel::getInstance()->getTableName(),
            ManageUserLoginModel::getInstance()->getTableName(),
            ManageUserGroupModel::getInstance()->getTableName(),
            ManageUserGroupLinkModel::getInstance()->getTableName(),
            ManageMenuModel::getInstance()->getTableName(),
            ManageMenuLinkModel::getInstance()->getTableName()
        ];
        foreach ($tables as $table) {
            Db::query('truncate table ' . $table);
        }
    }

    protected function initUser()
    {
        $user = [
            'user_name' => 'admin',
            'user_password' => ManageUserData::getSingleton()->encryptPassword('12345'),
            'user_nick' => '管理员',
            'user_status' => ManageUserConstant::STATUS_ENABLE
        ];
        ManageUserModel::create($user);
    }

    protected function initUserGroup()
    {
        $group = [
            'group_name' => '管理员',
            'group_pno' => '',
            'group_info' => '管理整个网站...'
        ];
        ManageUserGroupModel::create($group);
    }

    protected function initUserGroupLink()
    {
        $user = ManageUserModel::getInstance()->find();
        $group = ManageUserGroupModel::getInstance()->find();
        $link = [
            'user_no' => $user['user_no'],
            'group_no' => $group['group_no']
        ];
        ManageUserGroupLinkModel::create($link);
    }

    protected function initMenu()
    {
        $userMenu = [
            'menu_name' => '用户管理',
            'menu_icon' => 'fa fa-user',
            'menu_url' => 'manage/user/index',
            'menu_action' => 'manage/user/index',
            'menu_build' => ManageMenuConstant::BUILD_YES,
            'menu_type' => ManageMenuConstant::TYPE_MENU,
            'children' => [
                [
                    'menu_name' => '新增用户',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user/add',
                    'menu_action' => 'manage/user/add',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '编辑用户',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user/edit',
                    'menu_action' => 'manage/user/edit',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '更改用户',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user/modify',
                    'menu_action' => 'manage/user/modify',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '删除用户',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user/delete',
                    'menu_action' => 'manage/user/delete',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '选择群组',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user/auth',
                    'menu_action' => 'manage/user/auth',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ]
            ]
        ];

        $userGroupMenu = [
            'menu_name' => '群组管理',
            'menu_icon' => 'fa fa-users',
            'menu_url' => 'manage/user_group/index',
            'menu_action' => 'manage/user_group/index',
            'menu_build' => ManageMenuConstant::BUILD_YES,
            'menu_type' => ManageMenuConstant::TYPE_MENU,
            'children' => [
                [
                    'menu_name' => '新增群组',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user_group/add',
                    'menu_action' => 'manage/user_group/add',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '编辑群组',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user_group/edit',
                    'menu_action' => 'manage/user_group/edit',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '拖动群组',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user_group/drag',
                    'menu_action' => 'manage/user_group/drag',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '删除群组',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user_group/delete',
                    'menu_action' => 'manage/user_group/delete',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '群组菜单',
                    'menu_icon' => '',
                    'menu_url' => 'manage/user_group/auth',
                    'menu_action' => 'manage/user_group/auth',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ]
            ]
        ];

        $menuMenu = [
            'menu_name' => '菜单管理',
            'menu_icon' => 'fa fa-align-left',
            'menu_url' => 'manage/menu/index',
            'menu_action' => 'manage/menu/index',
            'menu_build' => ManageMenuConstant::BUILD_YES,
            'menu_type' => ManageMenuConstant::TYPE_MENU,
            'children' => [
                [
                    'menu_name' => '新增菜单',
                    'menu_icon' => '',
                    'menu_url' => 'manage/menu/add',
                    'menu_action' => 'manage/menu/add',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '编辑菜单',
                    'menu_icon' => '',
                    'menu_url' => 'manage/menu/edit',
                    'menu_action' => 'manage/menu/edit',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '更改菜单',
                    'menu_icon' => '',
                    'menu_url' => 'manage/menu/modify',
                    'menu_action' => 'manage/menu/modify',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '拖动菜单',
                    'menu_icon' => '',
                    'menu_url' => 'manage/menu/drag',
                    'menu_action' => 'manage/menu/drag',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ],
                [
                    'menu_name' => '删除菜单',
                    'menu_icon' => '',
                    'menu_url' => 'manage/menu/delete',
                    'menu_action' => 'manage/menu/delete',
                    'menu_build' => ManageMenuConstant::BUILD_YES,
                    'menu_type' => ManageMenuConstant::TYPE_ACTION
                ]
            ]
        ];

        $menus = [
            [
                'menu_name' => '后台',
                'menu_icon' => 'fa fa-compass',
                'menu_url' => '',
                'menu_action' => '',
                'menu_build' => ManageMenuConstant::BUILD_YES,
                'menu_type' => ManageMenuConstant::TYPE_MENU,
                'children' => [
                    [
                        'menu_name' => '控制台',
                        'menu_icon' => 'fa fa-dashboard',
                        'menu_url' => 'manage/index/index',
                        'menu_action' => 'manage/index/index',
                        'menu_build' => ManageMenuConstant::BUILD_YES,
                        'menu_type' => ManageMenuConstant::TYPE_MENU,
                        'children' => [
                            [
                                'menu_name' => '账号设置',
                                'menu_icon' => '',
                                'menu_url' => 'manage/user/account',
                                'menu_action' => 'manage/user/account',
                                'menu_build' => ManageMenuConstant::BUILD_YES,
                                'menu_type' => ManageMenuConstant::TYPE_ACTION
                            ],
                            [
                                'menu_name' => '上传文件',
                                'menu_icon' => '',
                                'menu_url' => 'manage/upload/upload',
                                'menu_action' => 'manage/upload/upload',
                                'menu_build' => ManageMenuConstant::BUILD_YES,
                                'menu_type' => ManageMenuConstant::TYPE_ACTION
                            ]
                        ]
                    ],
                    $userMenu,
                    $userGroupMenu,
                    $menuMenu,
                    [
                        'menu_name' => '缓存清理',
                        'menu_icon' => 'fa fa-trash',
                        'menu_url' => 'manage/index/runtime',
                        'menu_action' => 'manage/index/runtime',
                        'menu_build' => ManageMenuConstant::BUILD_YES,
                        'menu_type' => ManageMenuConstant::TYPE_MENU
                    ],
                    [
                        'menu_name' => '登录日志',
                        'menu_icon' => 'fa fa-commenting-o',
                        'menu_url' => 'manage/user_login/index',
                        'menu_action' => 'manage/user_login/index',
                        'menu_build' => ManageMenuConstant::BUILD_YES,
                        'menu_type' => ManageMenuConstant::TYPE_MENU
                    ]
                ]
            ],
            [
                'menu_name' => '组件',
                'menu_icon' => 'fa fa-cubes',
                'menu_url' => '',
                'menu_action' => '',
                'menu_build' => ManageMenuConstant::BUILD_YES,
                'menu_type' => ManageMenuConstant::TYPE_MENU,
                'children' => [
                    [
                        'menu_name' => '表单组件',
                        'menu_icon' => 'fa fa-edit',
                        'menu_url' => '@module/widget/index/form',
                        'menu_action' => 'module/widget/index/form',
                        'menu_build' => ManageMenuConstant::BUILD_YES,
                        'menu_type' => ManageMenuConstant::TYPE_MENU
                    ]
                ]
            ]
        ];

        foreach ($menus as $menu) {
            $this->createMenu($menu, ManageMenuConstant::ROOT_PNO_VALUE);
        }
    }

    protected function createMenu($menu, $menuPno)
    {
        if (isset($menu['children'])) {
            $copy = $menu;
            unset($copy['children']);
            $parentMenu = $this->createMenu($copy, $menuPno);

            foreach ($menu['children'] as $vo) {
                $this->createMenu($vo, $parentMenu['menu_no']);
            }
        } else {
            $menu['menu_pno'] = $menuPno;
            return ManageMenuModel::create($menu);
        }
    }

    protected function initMenuLink()
    {
        $group = ManageUserGroupModel::getInstance()->find();
        $menus = ManageMenuModel::getInstance()->select();
        foreach ($menus as $menu) {
            ManageMenuLinkModel::create([
                'group_no' => $group['group_no'],
                'menu_no' => $menu['menu_no']
            ]);
        }
    }
}
