# NewDayCms - 哩呵后台管理系统

![](https://img.shields.io/github/stars/newday-me/think-cms.svg) ![](https://img.shields.io/github/forks/newday-me/think-cms.svg) ![](https://img.shields.io/github/tag/newday-me/think-cms.svg)

**演示地址：[http://cms.newday.me](http://cms.newday.me "http://cms.newday.me")。**

## 一、CMS定位
通用后台管理的脚手架

## 二、CMS安装

* **使用[Composer](http://www.phpcomposer.com/ "Composer")安装**

运行【composer create-project newday-me/think-cms C:/www/cms 1.0.0】

*  **下载完整代码包**

从【[http://cms.newday.me/download.html](http://cms.newday.me/download.html "http://cms.newday.me/download.html")】下载压缩包，然后直接解压。

*  **安装数据库**

配置好【.env】文件，运行【php think migrate:run】

## 三、CMS分层

CMS的数据流方向如下图：

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/assets/image/flow.png)

* **modal**
模型层：一个model对应一张表，负责数据库的操作。**数据库操作。**

* **data**
数据层：一个modal对应一个data，实现特定的数据操作。调用model进行数据库操作，调用其他data进行数据库操作，数据的加密等处理。**数据操作，不涉及业务。**

* **logic**
逻辑层：远程数据的管理（如：远程接口调用封装），业务（如：上传，菜单树构建），调用其他逻辑层进行业务操作。**处理业务和精简服务层。**

* **service**
服务层：调用数据层操作数据，调用逻辑层处理业务。**尽量精简，调用为主。**

* **controller**
应用层：接收和输出数据。**输入和输出。**

## 四、CMS表单

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
{$widget->form('summer_note', ['title' => '文章内容', 'name' => 'article_content', 'value' => ''])}
```

## 五、建议反馈

有问题或者建议，欢迎邮件至【newday_me@163.com】。

因空余时间不多，回复慢还请见谅。