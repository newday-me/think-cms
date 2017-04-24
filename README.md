# NewDayCms - 哩呵后台管理系统

![](https://img.shields.io/github/stars/newday-me/think-cms.svg) ![](https://img.shields.io/github/forks/newday-me/think-cms.svg) ![](https://img.shields.io/github/tag/newday-me/think-cms.svg)

**演示地址：[http://cms.newday.me](http://cms.newday.me "http://cms.newday.me")，开发文档：[http://www.kancloud.cn/newday_me/think-cms](http://www.kancloud.cn/newday_me/think-cms "http://www.kancloud.cn/newday_me/think-cms")。**
**CMS交流群**：**482165089**。

## 一、安装CMS

* **使用[Composer](http://www.phpcomposer.com/ "Composer")安装**

直接运行【composer create-project newday-me/think-cms path version】即可

*  **下载完整代码包**

从【[http://cms.newday.me/download.html](http://cms.newday.me/download.html "http://cms.newday.me/download.html")】下载压缩包，然后直接解压。

## 二、功能设计

CMS的功能设计主要参考了OneThink，并实现了以下模块：

* 用户管理
* 权限管理
* 配置管理
* 缓存管理
* 菜单管理
* 附件管理
* 数据库备份
* 文章管理
* 队列管理
* 文章管理

## 三、表单组件化

只需要简单地配置，就可以快速生成表单项。
已支持生成的表单项有：文本、文本域、标签、时间、颜色、图片、文件、单选、多选、下拉框、编辑器、JSON。

* **文本**

```php
{$widget->form('text', ['title' => '用户昵称', 'name' => 'user_nick', 'value' => ''])}
```

* **标签**

```php
{$widget->form('tag', ['title' => '文章标签', 'name' => 'article_tags', 'value' => ''])}
```

* **图片**

```php
{$widget->form('image', ['title' => '文章封面', 'name' => 'article_cover', 'value' => ''])}
```

* **下拉选择**

```php
{$widget->form('select', ['title' => '文章分类', 'name' => 'article_cate', 'list' => $cate_list])}
```

* **编辑器**

```php
{$widget->form('editor', ['title' => '文章内容', 'name' => 'article_content', 'value' => ''])}
```

## 四、演示截图

* **控制台**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/image/index.png)

* **配置管理**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/image/config.png)

* **权限管理**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/image/auth.png)

* **菜单管理**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/image/menu.png)

* **网站设置**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/image/setting.png)

* **添加文章**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/image/article.png)