/*
Navicat MySQL Data Transfer

Source Server         : newday
Source Server Version : 50632
Source Host           : www.newday.me:3306
Source Database       : temp

Target Server Type    : MYSQL
Target Server Version : 50632
File Encoding         : 65001

Date: 2017-05-26 10:30:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `nd_blog_article`
-- ----------------------------
DROP TABLE IF EXISTS `nd_blog_article`;
CREATE TABLE `nd_blog_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_key` varchar(16) NOT NULL COMMENT '文章标识',
  `article_title` varchar(150) NOT NULL DEFAULT '' COMMENT '文章标题',
  `article_author` varchar(30) NOT NULL DEFAULT '' COMMENT '文章作者',
  `article_info` varchar(250) NOT NULL DEFAULT '' COMMENT '文章简介',
  `article_cover` varchar(250) NOT NULL DEFAULT '' COMMENT '文章封面',
  `article_origin` varchar(250) NOT NULL DEFAULT '' COMMENT '原文链接',
  `article_sort` int(11) NOT NULL DEFAULT '0' COMMENT '文章排序',
  `article_content` mediumtext NOT NULL COMMENT '文章内容',
  `article_visit` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `article_status` int(4) NOT NULL DEFAULT '0' COMMENT '文章状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_key` (`article_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_blog_article
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_blog_article_cate`
-- ----------------------------
DROP TABLE IF EXISTS `nd_blog_article_cate`;
CREATE TABLE `nd_blog_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_title` varchar(30) NOT NULL COMMENT '分类名称',
  `cate_name` varchar(20) NOT NULL COMMENT '分类标识',
  `cate_pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类',
  `cate_info` varchar(150) NOT NULL DEFAULT '' COMMENT '分类介绍',
  `cate_sort` int(11) NOT NULL DEFAULT '0' COMMENT '分类排序',
  `cate_status` int(4) NOT NULL DEFAULT '1' COMMENT '分类状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cate_name` (`cate_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_blog_article_cate
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_blog_article_cate_link`
-- ----------------------------
DROP TABLE IF EXISTS `nd_blog_article_cate_link`;
CREATE TABLE `nd_blog_article_cate_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '文章ID',
  `cate_id` int(11) NOT NULL COMMENT '分类ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_id` (`article_id`,`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_blog_article_cate_link
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_blog_article_tag`
-- ----------------------------
DROP TABLE IF EXISTS `nd_blog_article_tag`;
CREATE TABLE `nd_blog_article_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(20) NOT NULL DEFAULT '' COMMENT '标签名称',
  `tag_status` int(4) NOT NULL DEFAULT '1' COMMENT '标签状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_name` (`tag_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_blog_article_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_blog_article_tag_link`
-- ----------------------------
DROP TABLE IF EXISTS `nd_blog_article_tag_link`;
CREATE TABLE `nd_blog_article_tag_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '文章ID',
  `tag_id` int(11) NOT NULL COMMENT '标签ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_id` (`article_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_blog_article_tag_link
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_blog_page`
-- ----------------------------
DROP TABLE IF EXISTS `nd_blog_page`;
CREATE TABLE `nd_blog_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(50) NOT NULL COMMENT '标题',
  `page_name` varchar(30) NOT NULL COMMENT '页面名称',
  `page_content` mediumtext NOT NULL COMMENT '页面内容',
  `page_sort` int(11) NOT NULL DEFAULT '0' COMMENT '页面排序',
  `page_status` int(11) NOT NULL DEFAULT '0' COMMENT '页面状态，0：待发布，1：发布，2：菜单项',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_blog_page
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_manage_backup`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_backup`;
CREATE TABLE `nd_manage_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `backup_uid` int(11) NOT NULL COMMENT '用户ID',
  `backup_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `backup_file` varchar(150) NOT NULL COMMENT '导出文件',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_backup
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_manage_config`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_config`;
CREATE TABLE `nd_manage_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(30) NOT NULL COMMENT '配置名称',
  `config_value` varchar(1000) NOT NULL DEFAULT '' COMMENT '配置值',
  `config_type` varchar(10) NOT NULL COMMENT '配置类型',
  `config_title` varchar(30) NOT NULL DEFAULT '' COMMENT '配置标题',
  `config_gid` int(11) NOT NULL DEFAULT '0' COMMENT '配置分组',
  `config_extra` text NOT NULL COMMENT '额外配置',
  `config_sort` int(11) NOT NULL DEFAULT '0' COMMENT '配置排序',
  `config_remark` varchar(150) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_config
-- ----------------------------
INSERT INTO `nd_manage_config` VALUES ('1', 'app_debug', '1', 'radio', '调试模式', '1', '0:关闭|1:开启', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('2', 'app_trace', '0', 'radio', '应用Trace', '1', '0:关闭|1:开启', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('3', 'url_convert', '0', 'radio', '转换URL', '1', '0:不转换|1:转换', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('4', 'url_route_on', '0', 'radio', '开启路由', '1', '0:关闭|1:开启', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('5', 'url_route_must', '0', 'radio', '强制路由', '1', '0:不强制|1:强制使用', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('6', 'url_domain_deploy', '0', 'radio', '域名部署', '1', '0:否|1:是', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('7', 'url_domain_root', '', 'text', '网站域名', '1', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('8', 'log', '{\"type\":\"File\",\"path\":\"{LOG_PATH}\",\"level\":\"\"}', 'array', '日志设置', '1', 'type,path,level', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('9', 'trace', '{\"type\":\"Html\"}', 'array', 'Trace设置', '1', 'type', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('10', 'cache', '{\"type\":\"File\",\"path\":\"{CACHE_PATH}\",\"prefix\":\"\",\"expire\":0}', 'array', '缓存设置', '1', 'type,path,prefix,expire', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('11', 'session', '{\"id\":\"\",\"var_session_id\":\"\",\"prefix\":\"think\",\"type\":\"\",\"auto_start\":1}', 'array', '会话设置', '1', 'id,var_session_id,prefix,type,auto_start', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('12', 'cookie', '{\"prefix\":\"\",\"expire\":0,\"path\":\"\\/\",\"domain\":\"\",\"secure\":0,\"httponly\":\"\",\"setcookie\":1}', 'array', 'Cookie设置', '1', 'prefix,expire,path,domain,secure,httponly,setcookie', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('13', 'site_title', 'NewDayCms - 哩呵后台管理系统', 'text', '网站标题', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('14', 'site_version', '2017-05-26', 'text', '网站版本', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('15', 'site_base', '/', 'text', '网站目录', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('16', 'site_keyword', '哩呵,CMS,ThinkPHP,后台,管理系统', 'tag', '网站关键字', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('17', 'site_description', 'NewdayCms ，简单的方式管理数据。期待你的参与，共同打造一个功能更强大的通用后台管理系统。', 'textarea', '网站描述', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('18', 'login_driver', 'session', 'radio', '登录驱动', '2', 'session:Session驱动|cache:Cache驱动', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('19', 'crypt', '{\"mode\":\"cbc\",\"key\":\"kiLXtqGvaAdBzKzx\",\"iv\":\"wPeN9D96NdtbcTZN\"}', 'array', '加密配置', '2', 'mode,key,iv', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('20', 'bakup_path', '{ROOT_PATH}database/backups', 'text', '备份路径', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('21', 'cms_file', 'http://static.newday.me/cms/0.0.3.zip', 'file', 'CMS文件', '2', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('22', 'upload_driver', 'local', 'radio', '上传驱动', '3', 'local:本地|upyun:又拍云|qiniu:七牛云|ftp:FTP', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('23', 'upload_local', '{\"root\":\"{WEB_PATH}\",\"dir\":\"upload\\/\",\"base_url\":\"http:\\/\\/www.domain.com\\/\"}', 'array', '本地上传', '3', 'root,dir,base_url', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('24', 'upload_upyun', '{\"root\":\"\\/\",\"dir\":\"upload\\/\",\"base_url\":\"http:\\/\\/demo.b0.aicdn.com\\/\",\"bucket\":\"\",\"user\":\"\",\"passwd\":\"\",\"api_key\":\"\",\"max_size\":\"3M\",\"block_size\":\"1M\",\"return_url\":\"\",\"notify_url\":\"\"}', 'array', '又拍云上传', '3', 'root,dir,base_url,bucket,user,passwd,api_key,max_size,block_size,return_url,notify_url', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('25', 'upload_qiniu', '{\"root\":\"\\/\",\"dir\":\"upload\\/\",\"base_url\":\"http:\\/\\/demo.bkt.clouddn.com\\/\",\"bucket\":\"\",\"akey\":\"\",\"skey\":\"\"}', 'array', '七牛云上传', '3', 'root,dir,base_url,bucket,akey,skey', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('26', 'upload_ftp', '{\"root\":\"\\/\",\"dir\":\"upload\\/\",\"base_url\":\"http:\\/\\/demo.b0.aicdn.com\\/\",\"host\":\"v0.ftp.upyun.com\",\"ssl\":0,\"port\":21,\"timeout\":60,\"user\":\"user\\/bucket\",\"passwd\":\"passwd\"}', 'array', 'FTP上传', '3', 'root,dir,base_url,host,ssl,port,timeout,user,passwd', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('27', 'manage_verify_code', '1', 'radio', '开启验证码', '4', '0:不开启|1:开启', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('28', 'manage_html_minify', '1', 'radio', '压缩HTML', '4', '0:不压缩|1:压缩', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('29', 'manage_rows_num', '10', 'text', '每页条数', '4', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('30', 'manage_public_action', '[\"manage.start.*\"]', 'array', '公共行为', '4', '', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('31', 'manage_editor', 'ueditor', 'radio', '编辑器', '4', 'wang:wangEditor|ueditor:Ueditor', '0', '', '1495765798', '1495765798');
INSERT INTO `nd_manage_config` VALUES ('32', 'blog_title', '哩呵博客', 'text', '博客名称', '5', '', '0', '', '1495765799', '1495765799');
INSERT INTO `nd_manage_config` VALUES ('33', 'blog_head', 'http://static.newday.me/cms/head.jpg', 'image', '博客头像', '5', '', '0', '', '1495765799', '1495765799');
INSERT INTO `nd_manage_config` VALUES ('34', 'blog_head_background', 'http://static.newday.me/cms/head_background.jpg', 'image', '头像背景', '5', '', '0', '', '1495765799', '1495765799');
INSERT INTO `nd_manage_config` VALUES ('35', 'blog_desc', '希望死后的墓志铭可以有底气刻上</br>一生努力，一生被爱<br/>想要的都拥有，得不到的都释怀', 'textarea', '博客说明', '5', '', '0', '', '1495765799', '1495765799');
INSERT INTO `nd_manage_config` VALUES ('36', 'blog_404_background', 'http://static.newday.me/cms/404.jpg', 'image', '错误页背景', '5', '', '0', '', '1495765799', '1495765799');

-- ----------------------------
-- Table structure for `nd_manage_config_group`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_config_group`;
CREATE TABLE `nd_manage_config_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(30) NOT NULL COMMENT '分组名称',
  `group_info` varchar(250) NOT NULL DEFAULT '' COMMENT '分组描述',
  `group_sort` int(11) NOT NULL DEFAULT '0' COMMENT '分组排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_config_group
-- ----------------------------
INSERT INTO `nd_manage_config_group` VALUES ('1', '系统', '系统配置..', '0', '1495765798', '1495765798');
INSERT INTO `nd_manage_config_group` VALUES ('2', '网站', '网站配置..', '0', '1495765798', '1495765798');
INSERT INTO `nd_manage_config_group` VALUES ('3', '上传', '上传配置..', '0', '1495765798', '1495765798');
INSERT INTO `nd_manage_config_group` VALUES ('4', '后台', '后台配置..', '0', '1495765798', '1495765798');
INSERT INTO `nd_manage_config_group` VALUES ('5', '博客', '博客配置..', '0', '1495765799', '1495765799');

-- ----------------------------
-- Table structure for `nd_manage_file`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_file`;
CREATE TABLE `nd_manage_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_hash` varchar(32) NOT NULL COMMENT '文件哈希',
  `file_ext` varchar(10) NOT NULL COMMENT '文件后缀',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_path` varchar(100) NOT NULL COMMENT '文件路径',
  `file_url` varchar(150) NOT NULL COMMENT '文件链接',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_hash` (`file_hash`),
  KEY `file_ext` (`file_ext`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_file
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_manage_job`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_job`;
CREATE TABLE `nd_manage_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue` varchar(250) NOT NULL COMMENT '队列名',
  `payload` longtext NOT NULL COMMENT '数据',
  `attempts` int(5) NOT NULL COMMENT '尝试次数',
  `reserved` int(5) NOT NULL COMMENT '发布次数',
  `reserved_at` int(11) DEFAULT NULL COMMENT '发布时间',
  `available_at` int(11) NOT NULL COMMENT '执行时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_job
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_manage_menu`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_menu`;
CREATE TABLE `nd_manage_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `menu_pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级菜单',
  `menu_url` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单链接',
  `menu_flag` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单标识',
  `menu_build` int(4) NOT NULL DEFAULT '1' COMMENT '是否Build',
  `menu_target` varchar(10) NOT NULL DEFAULT '_self' COMMENT '打开方式',
  `menu_sort` int(11) NOT NULL DEFAULT '0' COMMENT '菜单排序',
  `menu_status` int(4) NOT NULL DEFAULT '1' COMMENT '菜单状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `menu_pid` (`menu_pid`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_menu
-- ----------------------------
INSERT INTO `nd_manage_menu` VALUES ('1', '系统', '0', '', '', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('2', '网站', '1', '', '', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('3', '控制台', '2', 'manage/index/index', 'manage/index/index', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('4', '账号设置', '3', 'manage/index/account', 'manage/index/account', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('5', '网站设置', '2', 'manage/config/setting', 'manage/config/setting', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('6', '菜单管理', '2', 'manage/menu/index', 'manage/menu/index', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('7', '新增菜单', '6', 'manage/menu/add', 'manage/menu/add', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('8', '编辑菜单', '6', 'manage/menu/edit', 'manage/menu/edit', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('9', '更改菜单', '6', 'manage/menu/modify', 'manage/menu/modify', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('10', '菜单排序', '6', 'manage/menu/sort', 'manage/menu/sort', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('11', '删除菜单', '6', 'manage/menu/delete', 'manage/menu/delete', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('12', '缓存清理', '2', 'manage/index/runtime', 'manage/index/runtime', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('13', '配置', '1', '', '', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('14', '配置分组', '13', 'manage/config_group/index', 'manage/config_group/index', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('15', '新增分组', '14', 'manage/config_group/add', 'manage/config_group/add', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('16', '编辑分组', '14', 'manage/config_group/edit', 'manage/config_group/edit', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('17', '更改分组', '14', 'manage/config_group/modify', 'manage/config_group/modify', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('18', '分组排序', '14', 'manage/config_group/sort', 'manage/config_group/sort', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('19', '删除分组', '14', 'manage/config_group/delete', 'manage/config_group/delete', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('20', '配置列表', '13', 'manage/config/index', 'manage/config/index', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('21', '新增配置', '20', 'manage/config/add', 'manage/config/add', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('22', '编辑配置', '20', 'manage/config/edit', 'manage/config/edit', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('23', '更改配置', '20', 'manage/config/modify', 'manage/config/modify', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('24', '配置排序', '20', 'manage/config/sort', 'manage/config/sort', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('25', '删除配置', '20', 'manage/config/delete', 'manage/config/delete', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('26', '用户', '1', '', '', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('27', '用户群组', '26', 'manage/user_group/index', 'manage/user_group/index', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('28', '新增群组', '27', 'manage/user_group/add', 'manage/user_group/add', '1', '_self', '0', '1', '1495765797', '1495765797');
INSERT INTO `nd_manage_menu` VALUES ('29', '编辑群组', '27', 'manage/user_group/edit', 'manage/user_group/edit', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('30', '群组权限', '27', 'manage/user_group/auth', 'manage/user_group/auth', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('31', '更改群组', '27', 'manage/user_group/modify', 'manage/user_group/modify', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('32', '删除群组', '27', 'manage/user_group/delete', 'manage/user_group/delete', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('33', '用户管理', '26', 'manage/user/index', 'manage/user/index', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('34', '新增用户', '33', 'manage/user/add', 'manage/user/add', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('35', '编辑用户', '33', 'manage/user/edit', 'manage/user/edit', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('36', '更改用户', '33', 'manage/user/modify', 'manage/user/modify', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('37', '删除用户', '33', 'manage/user/delete', 'manage/user/delete', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('38', '登录日志', '26', 'manage/user_login/index', 'manage/user_login/index', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('39', '队列', '1', '', '', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('40', '任务管理', '39', 'manage/job/index', 'manage/job/index', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('41', '任务延时', '40', 'manage/job/delay', 'manage/job/delay', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('42', '更改任务', '40', 'manage/job/modify', 'manage/job/modify', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('43', '删除任务', '40', 'manage/job/delete', 'manage/job/delete', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('44', '文件', '1', '', '', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('45', '上传文件', '44', 'manage/file/upload', 'manage/file/upload', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('46', '普通上传', '45', 'manage/upload/upload', 'manage/upload/upload', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('47', '编辑器上传', '45', 'manage/upload/editor', 'manage/upload/editor', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('48', '附件管理', '44', 'manage/file/index', 'manage/file/index', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('49', '删除文件', '48', 'manage/file/delete', 'manage/file/delete', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('50', '数据库', '1', '', '', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('51', '数据备份', '50', 'manage/backup/index', 'manage/backup/index', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('52', '备份数据库', '51', 'manage/backup/backup', 'manage/backup/backup', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('53', '优化数据库', '51', 'manage/backup/optimize', 'manage/backup/optimize', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('54', '修复数据库', '51', 'manage/backup/repair', 'manage/backup/repair', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('55', '备份记录', '50', 'manage/backup/log', 'manage/backup/log', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('56', '删除备份', '55', 'manage/backup/delete', 'manage/backup/delete', '1', '_self', '0', '1', '1495765798', '1495765798');
INSERT INTO `nd_manage_menu` VALUES ('57', '博客', '0', '', '', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('58', '文章', '57', '', '', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('59', '文章分类', '58', '@module/blog/article_cate/index', 'module/blog/article_cate/index', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('60', '新增分类', '59', '@module/blog/article_cate/add', 'module/blog/article_cate/add', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('61', '编辑分类', '59', '@module/blog/article_cate/edit', 'module/blog/article_cate/edit', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('62', '更改分类', '59', '@module/blog/article_cate/modify', 'module/blog/article_cate/modify', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('63', '删除分类', '59', '@module/blog/article_cate/delete', 'module/blog/article_cate/delete', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('64', '文章标签', '58', '@module/blog/article_tag/index', 'module/blog/article_tag/index', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('65', '更改标签', '64', '@module/blog/article_tag/modify', 'module/blog/article_tag/modify', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('66', '删除标签', '64', '@module/blog/article_tag/delete', 'module/blog/article_tag/delete', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('67', '文章管理', '58', '@module/blog/article/index', 'module/blog/article/index', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('68', '新增文章', '67', '@module/blog/article/add', 'module/blog/article/add', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('69', '编辑文章', '67', '@module/blog/article/edit', 'module/blog/article/edit', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('70', '更改文章', '67', '@module/blog/article/modify', 'module/blog/article/modify', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('71', '删除文章', '67', '@module/blog/article/delete', 'module/blog/article/delete', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('72', '恢复文章', '67', '@module/blog/article/recover', 'module/blog/article/recover', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('73', '文章回收站', '58', '@module/blog/article/recycle', 'module/blog/article/recycle', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('74', '页面', '57', '', '', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('75', '页面管理', '74', '@module/blog/page/index', 'module/blog/page/index', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('76', '新增页面', '75', '@module/blog/page/add', 'module/blog/page/add', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('77', '编辑页面', '75', '@module/blog/page/edit', 'module/blog/page/edit', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('78', '更改页面', '75', '@module/blog/page/modify', 'module/blog/page/modify', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('79', '删除页面', '75', '@module/blog/page/delete', 'module/blog/page/delete', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('80', '恢复页面', '75', '@module/blog/page/recover', 'module/blog/page/recover', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('81', '页面回收站', '74', '@module/blog/page/recycle', 'module/blog/page/recycle', '1', '_self', '0', '1', '1495765799', '1495765799');
INSERT INTO `nd_manage_menu` VALUES ('82', '恢复页面', '81', '@module/blog/page/recover', 'module/blog/page/recover', '1', '_self', '0', '1', '1495765799', '1495765799');

-- ----------------------------
-- Table structure for `nd_manage_user`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_user`;
CREATE TABLE `nd_manage_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(16) NOT NULL COMMENT '用户名',
  `user_passwd` varchar(32) NOT NULL COMMENT '登录密码',
  `user_nick` varchar(150) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_gid` int(11) NOT NULL COMMENT '用户分组',
  `user_status` int(4) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登录IP',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_user
-- ----------------------------
INSERT INTO `nd_manage_user` VALUES ('1', 'admin', '75990d0a7e95e01c7f372f540e9b992a', '管理员', '1', '1', '0', '', '0', '1495765798', '1495765798');

-- ----------------------------
-- Table structure for `nd_manage_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_user_group`;
CREATE TABLE `nd_manage_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) NOT NULL DEFAULT '' COMMENT '群组名称',
  `group_info` varchar(80) NOT NULL DEFAULT '' COMMENT '群组描述',
  `home_page` varchar(150) NOT NULL DEFAULT '' COMMENT '管理首页',
  `group_menus` varchar(1000) NOT NULL DEFAULT '' COMMENT '群组菜单',
  `group_status` int(4) NOT NULL DEFAULT '0' COMMENT '群组状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_user_group
-- ----------------------------
INSERT INTO `nd_manage_user_group` VALUES ('1', '管理员', '管理网站', 'manage/index/index', '1,57,2,13,26,39,44,50,3,5,6,12,4,7,8,9,10,11,14,20,15,16,17,18,19,21,22,23,24,25,27,33,38,28,29,30,31,32,34,35,36,37,40,41,42,43,45,48,46,47,49,51,55,52,53,54,56,58,74,59,64,67,73,60,61,62,63,65,66,68,69,70,71,72,75,81,76,77,78,79,80,82', '1', '1495765798', '1495765799');

-- ----------------------------
-- Table structure for `nd_manage_user_login`
-- ----------------------------
DROP TABLE IF EXISTS `nd_manage_user_login`;
CREATE TABLE `nd_manage_user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_uid` int(11) NOT NULL COMMENT '用户ID',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登录IP',
  `login_agent` varchar(250) NOT NULL DEFAULT '' COMMENT '浏览器信息',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_manage_user_login
-- ----------------------------

-- ----------------------------
-- Table structure for `nd_migrations`
-- ----------------------------
DROP TABLE IF EXISTS `nd_migrations`;
CREATE TABLE `nd_migrations` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nd_migrations
-- ----------------------------
INSERT INTO `nd_migrations` VALUES ('20170505000010', 'CreateTableManageUser', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000015', 'CreateTableManageUserGroup', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000020', 'CreateTableManageUserLogin', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000025', 'CreateTableManageConfig', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000030', 'CreateTableManageConfigGroup', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000035', 'CreateTableManageMenu', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000040', 'CreateTableManageFile', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000045', 'CreateTableManageBackup', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000050', 'CreateTableManageJob', '2017-05-26 10:29:57', '2017-05-26 10:29:57', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000090', 'InitManageTableData', '2017-05-26 10:29:57', '2017-05-26 10:29:58', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000110', 'CreateTableBlogArticle', '2017-05-26 10:29:58', '2017-05-26 10:29:58', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000115', 'CreateTableBlogArticleCate', '2017-05-26 10:29:58', '2017-05-26 10:29:58', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000120', 'CreateTableBlogArticleArticleCateLink', '2017-05-26 10:29:59', '2017-05-26 10:29:59', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000125', 'CreateTableBlogArticleTag', '2017-05-26 10:29:59', '2017-05-26 10:29:59', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000130', 'CreateTableBlogArticleTagLink', '2017-05-26 10:29:59', '2017-05-26 10:29:59', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000135', 'CreateTableBlogPage', '2017-05-26 10:29:59', '2017-05-26 10:29:59', '0');
INSERT INTO `nd_migrations` VALUES ('20170505000195', 'InitBlogManageTableData', '2017-05-26 10:29:59', '2017-05-26 10:29:59', '0');
