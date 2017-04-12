<?php
use think\migration\Migrator;

use think\Db;
use think\helper\Str;
use core\manage\logic\UserLogic;
use core\manage\model\UserModel;
use core\manage\model\UserGroupModel;
use core\manage\model\MenuModel;
use core\manage\model\ConfigModel;

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
        // 菜单
        $model = MenuModel::getInstance();
        
        // 菜单-系统
        $data = [
            'menu_name' => '系统',
            'menu_pid' => 0,
            'menu_url' => '',
            'menu_group' => '',
            'menu_flag' => '',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $mainMain = $model->create($data);
        
        // 菜单-控制台
        $data = [
            'menu_name' => '控制台',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/index/index',
            'menu_group' => '网站',
            'menu_flag' => 'manage/index/index',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-控制台-账号设置
        $data = [
            'menu_name' => '编辑配置',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/index/account',
            'menu_group' => '',
            'menu_flag' => 'manage/index/account',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-网站设置
        $data = [
            'menu_name' => '网站设置',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/config/setting',
            'menu_group' => '网站',
            'menu_flag' => 'manage/config/setting',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-配置管理
        $data = [
            'menu_name' => '配置管理',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/config/index',
            'menu_group' => '网站',
            'menu_flag' => 'manage/config/index',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-配置管理-新增
        $data = [
            'menu_name' => '新增配置',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/config/add',
            'menu_group' => '',
            'menu_flag' => 'manage/config/add',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-配置管理-编辑
        $data = [
            'menu_name' => '编辑配置',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/config/edit',
            'menu_group' => '',
            'menu_flag' => 'manage/config/edit',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-配置管理-更改
        $data = [
            'menu_name' => '更改配置',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/config/modify',
            'menu_group' => '',
            'menu_flag' => 'manage/config/modify',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-配置管理-删除
        $data = [
            'menu_name' => '删除配置',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/config/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/config/delete',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-菜单管理
        $data = [
            'menu_name' => '菜单管理',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/menu/index',
            'menu_group' => '网站',
            'menu_flag' => 'manage/menu/index',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-菜单管理-新增
        $data = [
            'menu_name' => '新增菜单',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/menu/add',
            'menu_group' => '',
            'menu_flag' => 'manage/menu/add',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-菜单管理-编辑
        $data = [
            'menu_name' => '编辑菜单',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/menu/edit',
            'menu_group' => '',
            'menu_flag' => 'manage/menu/edit',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-菜单管理-修改
        $data = [
            'menu_name' => '修改菜单',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/menu/modify',
            'menu_group' => '',
            'menu_flag' => 'manage/menu/modify',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-菜单管理-删除
        $data = [
            'menu_name' => '删除菜单',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/menu/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/menu/delete',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-缓存清理
        $data = [
            'menu_name' => '缓存清理',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/index/runtime',
            'menu_group' => '网站',
            'menu_flag' => 'manage/index/runtime',
            'menu_sort' => 4,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-用户群组
        $data = [
            'menu_name' => '用户群组',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/user_group/index',
            'menu_group' => '用户',
            'menu_flag' => 'manage/user_group/index',
            'menu_sort' => 10,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-用户群组-新增
        $data = [
            'menu_name' => '新增群组',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user_group/add',
            'menu_group' => '',
            'menu_flag' => 'manage/user_group/add',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户群组-编辑
        $data = [
            'menu_name' => '编辑群组',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user_group/edit',
            'menu_group' => '',
            'menu_flag' => 'manage/user_group/edit',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户群组-更改
        $data = [
            'menu_name' => '更改群组',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user_group/modify',
            'menu_group' => '',
            'menu_flag' => 'manage/user_group/modify',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户群组-删除
        $data = [
            'menu_name' => '删除群组',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user_group/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/user_group/delete',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户群组-权限
        $data = [
            'menu_name' => '群组权限',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user_group/auth',
            'menu_group' => '',
            'menu_flag' => 'manage/user_group/auth',
            'menu_sort' => 4,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户
        $data = [
            'menu_name' => '用户管理',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/user/index',
            'menu_group' => '用户',
            'menu_flag' => 'manage/user/index',
            'menu_sort' => 11,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-用户-新增
        $data = [
            'menu_name' => '新增用户',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user/add',
            'menu_group' => '',
            'menu_flag' => 'manage/user/add',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户-编辑
        $data = [
            'menu_name' => '编辑用户',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user/edit',
            'menu_group' => '',
            'menu_flag' => 'manage/user/edit',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户-更改
        $data = [
            'menu_name' => '更改用户',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user/modify',
            'menu_group' => '',
            'menu_flag' => 'manage/user/modify',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-用户-删除
        $data = [
            'menu_name' => '删除用户',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/user/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/user/delete',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-登录日志
        $data = [
            'menu_name' => '登录日志',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/user_login/index',
            'menu_group' => '用户',
            'menu_flag' => 'manage/user_login/index',
            'menu_sort' => 12,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-队列管理
        $data = [
            'menu_name' => '队列管理',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/job/index',
            'menu_group' => '队列',
            'menu_flag' => 'manage/job/index',
            'menu_sort' => 20,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-队列管理-更改
        $data = [
            'menu_name' => '更改队列',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/job/modify',
            'menu_group' => '',
            'menu_flag' => 'manage/job/modify',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-队列管理-延时
        $data = [
            'menu_name' => '队列延时',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/job/delay',
            'menu_group' => '',
            'menu_flag' => 'manage/job/delay',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-队列管理-删除
        $data = [
            'menu_name' => '删除队列',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/job/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/job/delete',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-上传文件
        $data = [
            'menu_name' => '上传文件',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/file/upload',
            'menu_group' => '文件',
            'menu_flag' => 'manage/file/upload',
            'menu_sort' => 30,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-上传文件-普通
        $data = [
            'menu_name' => '普通上传',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/upload/upload',
            'menu_group' => '',
            'menu_flag' => 'manage/upload/upload',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-上传文件-编辑器
        $data = [
            'menu_name' => '编辑器上传',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/upload/editor',
            'menu_group' => '',
            'menu_flag' => 'manage/upload/editor',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-附件管理
        $data = [
            'menu_name' => '附件管理',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/file/index',
            'menu_group' => '文件',
            'menu_flag' => 'manage/file/index',
            'menu_sort' => 31,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-附件管理-删除
        $data = [
            'menu_name' => '删除文件',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/file/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/file/delete',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-数据备份
        $data = [
            'menu_name' => '数据备份',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/backup/index',
            'menu_group' => '数据库',
            'menu_flag' => 'manage/backup/index',
            'menu_sort' => 40,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-数据备份-备份
        $data = [
            'menu_name' => '备份数据库',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/backup/backup',
            'menu_group' => '',
            'menu_flag' => 'manage/backup/backup',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-数据备份-优化
        $data = [
            'menu_name' => '优化数据库',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/backup/optimize',
            'menu_group' => '',
            'menu_flag' => 'manage/backup/optimize',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-数据备份-修复
        $data = [
            'menu_name' => '修复数据库',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/backup/repair',
            'menu_group' => '',
            'menu_flag' => 'manage/backup/repair',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-备份记录
        $data = [
            'menu_name' => '备份记录',
            'menu_pid' => $mainMain['id'],
            'menu_url' => 'manage/backup/log',
            'menu_group' => '数据库',
            'menu_flag' => 'manage/backup/log',
            'menu_sort' => 41,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-数据备份-删除
        $data = [
            'menu_name' => '删除备份',
            'menu_pid' => $subMenu['id'],
            'menu_url' => 'manage/backup/delete',
            'menu_group' => '',
            'menu_flag' => 'manage/backup/delete',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 所有菜单ID
        $menus = $model->field('id')->select();
        $menuIds = [];
        foreach ($menus as $menu) {
            $menuIds[] = $menu['id'];
        }
        
        // 群组
        $model = UserGroupModel::getInstance();
        $data = [
            'group_name' => '管理员',
            'group_info' => '管理网站',
            'home_page' => 'manage/index/index',
            'group_menus' => implode(',', $menuIds),
            'group_status' => 1
        ];
        $group = $model->create($data);
        
        // 用户
        $model = UserModel::getInstance();
        $data = [
            'user_name' => 'admin',
            'user_passwd' => UserLogic::getSingleton()->encryptPasswd('123456'),
            'user_nick' => '管理员',
            'user_gid' => $group['id'],
            'user_status' => 1
        ];
        $user = $model->create($data);
        
        // 配置
        $model = ConfigModel::getInstance();
        
        // 配置-调试模式
        $data = [
            'config_name' => 'app_debug',
            'config_value' => 1,
            'config_type' => 'radio',
            'config_title' => '调试模式',
            'config_group' => '核心',
            'config_extra' => '0:关闭|1:开启',
            'config_sort' => 0
        ];
        $model->create($data);
        
        // 配置-应用Trace
        $data = [
            'config_name' => 'app_trace',
            'config_value' => 0,
            'config_type' => 'radio',
            'config_title' => '应用Trace',
            'config_group' => '核心',
            'config_extra' => '0:关闭|1:开启',
            'config_sort' => 1
        ];
        $model->create($data);
        
        // 配置-转换URL
        $data = [
            'config_name' => 'url_convert',
            'config_value' => 0,
            'config_type' => 'radio',
            'config_title' => '转换URL',
            'config_group' => '核心',
            'config_extra' => '0:不转换|1:转换',
            'config_sort' => 2
        ];
        $model->create($data);
        
        // 配置-开启路由
        $data = [
            'config_name' => 'url_route_on',
            'config_value' => 0,
            'config_type' => 'radio',
            'config_title' => '开启路由',
            'config_group' => '核心',
            'config_extra' => '0:关闭|1:开启',
            'config_sort' => 3
        ];
        $model->create($data);
        
        // 配置-强制路由
        $data = [
            'config_name' => 'url_route_must',
            'config_value' => 0,
            'config_type' => 'radio',
            'config_title' => '强制路由',
            'config_group' => '核心',
            'config_extra' => '0:不强制|1:强制使用',
            'config_sort' => 4
        ];
        $model->create($data);
        
        // 配置-域名部署
        $data = [
            'config_name' => 'url_domain_deploy',
            'config_value' => 0,
            'config_type' => 'radio',
            'config_title' => '域名部署',
            'config_group' => '核心',
            'config_extra' => '0:否|1:是',
            'config_sort' => 5
        ];
        $model->create($data);
        
        // 配置-网站域名
        $data = [
            'config_name' => 'url_domain_root',
            'config_value' => '',
            'config_type' => 'text',
            'config_title' => '网站域名',
            'config_group' => '核心',
            'config_extra' => '',
            'config_sort' => 6
        ];
        $model->create($data);
        
        // 配置-日志设置
        $option = [
            'type' => 'File',
            'path' => '{LOG_PATH}',
            'level' => ''
        ];
        $data = [
            'config_name' => 'log',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '日志设置',
            'config_group' => '核心',
            'config_extra' => 'type,path,level',
            'config_sort' => 7
        ];
        $model->create($data);
        
        // 配置-Trace设置
        $option = [
            'type' => 'Html'
        ];
        $data = [
            'config_name' => 'trace',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => 'Trace设置',
            'config_group' => '核心',
            'config_extra' => 'type',
            'config_sort' => 8
        ];
        $model->create($data);
        
        // 配置-缓存设置
        $option = [
            'type' => 'File',
            'path' => '{CACHE_PATH}',
            'prefix' => '',
            'expire' => 0
        ];
        $data = [
            'config_name' => 'cache',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '缓存设置',
            'config_group' => '核心',
            'config_extra' => 'type,path,prefix,expire',
            'config_sort' => 9
        ];
        $model->create($data);
        
        // 配置-会话设置
        $option = [
            'id' => '',
            'var_session_id' => '',
            'prefix' => 'think',
            'type' => '',
            'auto_start' => 1
        ];
        $data = [
            'config_name' => 'session',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '会话设置',
            'config_group' => '核心',
            'config_extra' => 'id,var_session_id,prefix,type,auto_start',
            'config_sort' => 10
        ];
        $model->create($data);
        
        // 配置-Cookie设置
        $option = [
            'prefix' => '',
            'expire' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => 0,
            'httponly' => '',
            'setcookie' => 1
        ];
        $data = [
            'config_name' => 'cookie',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => 'Cookie设置',
            'config_group' => '核心',
            'config_extra' => 'prefix,expire,path,domain,secure,httponly,setcookie',
            'config_sort' => 11
        ];
        $model->create($data);
        
        // 配置-网站标题
        $data = [
            'config_name' => 'site_title',
            'config_value' => 'NewDayCms - 哩呵后台管理系统',
            'config_type' => 'text',
            'config_title' => '网站标题',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 100
        ];
        $model->create($data);
        
        // 配置-网站版本
        $data = [
            'config_name' => 'site_version',
            'config_value' => date('Y-m-d'),
            'config_type' => 'text',
            'config_title' => '网站版本',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 101
        ];
        $model->create($data);
        
        // 配置-网站目录
        $data = [
            'config_name' => 'site_base',
            'config_value' => '/',
            'config_type' => 'text',
            'config_title' => '网站目录',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 102
        ];
        $model->create($data);
        
        // 配置-网站关键字
        $data = [
            'config_name' => 'site_keyword',
            'config_value' => '哩呵,CMS,ThinkPHP,后台,管理系统',
            'config_type' => 'tag',
            'config_title' => '网站关键字',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 103
        ];
        $model->create($data);
        
        // 配置-网站描述
        $data = [
            'config_name' => 'site_description',
            'config_value' => 'NewdayCms ，简单的方式管理数据。期待你的参与，共同打造一个功能更强大的通用后台管理系统。',
            'config_type' => 'textarea',
            'config_title' => '网站描述',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 104
        ];
        $model->create($data);
        
        // 配置-登录驱动
        $data = [
            'config_name' => 'login_driver',
            'config_value' => 'session',
            'config_type' => 'radio',
            'config_title' => '登录驱动',
            'config_group' => '网站',
            'config_extra' => 'session:Session驱动|cache:Cache驱动',
            'config_sort' => 105
        ];
        $model->create($data);
        
        // 配置-加密配置
        $option = [
            'mode' => 'cbc',
            'key' => Str::random(16),
            'iv' => Str::random(16)
        ];
        $data = [
            'config_name' => 'crypt',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '加密配置',
            'config_group' => '网站',
            'config_extra' => 'mode,key,iv',
            'config_sort' => 106
        ];
        $model->create($data);
        
        // 配置-备份路径
        $data = [
            'config_name' => 'bakup_path',
            'config_value' => '{ROOT_PATH}database/backups',
            'config_type' => 'text',
            'config_title' => '备份路径',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 107
        ];
        $model->create($data);
        
        // 配置-CMS文件
        $data = [
            'config_name' => 'cms_file',
            'config_value' => 'http://static.newday.me/cms/think-cms-0.0.2.zip',
            'config_type' => 'file',
            'config_title' => 'CMS文件',
            'config_group' => '网站',
            'config_extra' => '',
            'config_sort' => 108
        ];
        $model->create($data);
        
        // 配置-上传驱动
        $data = [
            'config_name' => 'upload_driver',
            'config_value' => 'local',
            'config_type' => 'radio',
            'config_title' => '上传驱动',
            'config_group' => '上传',
            'config_extra' => 'local:本地|upyun:又拍云|qiniu:七牛云|ftp:FTP',
            'config_sort' => 200
        ];
        $model->create($data);
        
        // 配置-本地上传
        $option = [
            'root' => '{WEB_PATH}',
            'dir' => 'upload/',
            'base_url' => 'http://www.domain.com/'
        ];
        $data = [
            'config_name' => 'upload_local',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '本地上传',
            'config_group' => '上传',
            'config_extra' => 'root,dir,base_url',
            'config_sort' => 201
        ];
        $model->create($data);
        
        // 配置-又拍云上传
        $option = [
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
        ];
        $data = [
            'config_name' => 'upload_upyun',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '又拍云上传',
            'config_group' => '上传',
            'config_extra' => 'root,dir,base_url,bucket,user,passwd,api_key,max_size,block_size,return_url,notify_url',
            'config_sort' => 202
        ];
        $model->create($data);
        
        // 配置-七牛云上传
        $option = [
            'root' => '/',
            'dir' => 'upload/',
            'base_url' => 'http://demo.bkt.clouddn.com/',
            'bucket' => '',
            'akey' => '',
            'skey' => ''
        ];
        $data = [
            'config_name' => 'upload_qiniu',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '七牛云上传',
            'config_group' => '上传',
            'config_extra' => 'root,dir,base_url,bucket,akey,skey',
            'config_sort' => 203
        ];
        $model->create($data);
        
        // 配置-FTP上传
        $option = [
            'root' => '/',
            'dir' => 'upload/',
            'base_url' => 'http://demo.b0.aicdn.com/',
            'host' => 'v0.ftp.upyun.com',
            'ssl' => 0,
            'port' => 21,
            'timeout' => 60,
            'user' => 'user/bucket',
            'passwd' => 'passwd'
        ];
        $data = [
            'config_name' => 'upload_ftp',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => 'FTP上传',
            'config_group' => '上传',
            'config_extra' => 'root,dir,base_url,host,ssl,port,timeout,user,passwd',
            'config_sort' => 204
        ];
        $model->create($data);
        
        // 后台-开启验证码
        $data = [
            'config_name' => 'manage_verify_code',
            'config_value' => 1,
            'config_type' => 'radio',
            'config_title' => '开启验证码',
            'config_group' => '后台',
            'config_extra' => '0:不开启|1:开启',
            'config_sort' => 300
        ];
        $model->create($data);
        
        // 后台-压缩HTML
        $data = [
            'config_name' => 'manage_html_minify',
            'config_value' => 1,
            'config_type' => 'radio',
            'config_title' => '压缩HTML',
            'config_group' => '后台',
            'config_extra' => '0:不压缩|1:压缩',
            'config_sort' => 301
        ];
        $model->create($data);
        
        // 后台-每页条数
        $data = [
            'config_name' => 'manage_rows_num',
            'config_value' => 10,
            'config_type' => 'text',
            'config_title' => '每页条数',
            'config_group' => '后台',
            'config_extra' => '',
            'config_sort' => 302
        ];
        $model->create($data);
        
        // 后台-公共行为
        $option = [
            'manage.start.*'
        ];
        $data = [
            'config_name' => 'manage_public_action',
            'config_value' => json_encode($option, JSON_UNESCAPED_UNICODE),
            'config_type' => 'array',
            'config_title' => '公共行为',
            'config_group' => '后台',
            'config_extra' => '',
            'config_sort' => 303
        ];
        $model->create($data);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        Db::connect()->query('truncate table nd_manage_user');
        Db::connect()->query('truncate table nd_manage_user_group');
        Db::connect()->query('truncate table nd_manage_user_login');
        Db::connect()->query('truncate table nd_manage_menu');
        Db::connect()->query('truncate table nd_manage_config');
        Db::connect()->query('truncate table nd_manage_file');
        Db::connect()->query('truncate table nd_manage_backup');
    }
}
