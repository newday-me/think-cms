<?php
use think\migration\Migrator;
use core\manage\model\MenuModel;
use core\manage\model\UserGroupModel;

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
        // 菜单
        $model = MenuModel::getSingleton();
        
        // 菜单-内容
        $data = [
            'menu_name' => '内容',
            'menu_pid' => 0,
            'menu_url' => '',
            'menu_group' => '',
            'menu_flag' => '',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $mainMain = $model->create($data);
        
        // 菜单-文章分类
        $data = [
            'menu_name' => '文章分类',
            'menu_pid' => $mainMain['id'],
            'menu_url' => '@module/blog/article_cate/index',
            'menu_group' => '博客',
            'menu_flag' => 'module/blog/article_cate/index',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-文章分类-新增
        $data = [
            'menu_name' => '新增分类',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article_cate/add',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article_cate/add',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章分类-编辑
        $data = [
            'menu_name' => '编辑分类',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article_cate/edit',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article_cate/edit',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章分类-更改
        $data = [
            'menu_name' => '更改分类',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article_cate/modify',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article_cate/modify',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章分类-删除
        $data = [
            'menu_name' => '删除分类',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article_cate/delete',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article_cate/delete',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章列表
        $data = [
            'menu_name' => '文章列表',
            'menu_pid' => $mainMain['id'],
            'menu_url' => '@module/blog/article/index',
            'menu_group' => '博客',
            'menu_flag' => 'module/blog/article/index',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $subMenu = $model->create($data);
        
        // 菜单-文章列表-新增
        $data = [
            'menu_name' => '新增文章',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article/add',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article/add',
            'menu_sort' => 0,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章列表-编辑
        $data = [
            'menu_name' => '编辑文章',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article/edit',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article/edit',
            'menu_sort' => 1,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章列表-更改
        $data = [
            'menu_name' => '更改文章',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article/modify',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article/modify',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-文章列表-删除
        $data = [
            'menu_name' => '删除文章',
            'menu_pid' => $subMenu['id'],
            'menu_url' => '@module/blog/article/delete',
            'menu_group' => '',
            'menu_flag' => 'module/blog/article/delete',
            'menu_sort' => 3,
            'menu_status' => 1
        ];
        $menu = $model->create($data);
        
        // 菜单-主页
        $data = [
            'menu_name' => '主页',
            'menu_pid' => 0,
            'menu_url' => '/index.html',
            'menu_group' => '',
            'menu_flag' => '',
            'menu_build' => false,
            'menu_target' => '_blank',
            'menu_sort' => 2,
            'menu_status' => 1
        ];
        $mainMain = $model->create($data);
        
        // 所有菜单ID
        $menus = $model->field('id')->select();
        $menuIds = [];
        foreach ($menus as $menu) {
            $menuIds[] = $menu['id'];
        }
        
        // 保存群组
        $group = UserGroupModel::get(1);
        $group['group_menus'] = implode(',', $menuIds);
        $group->save();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $model = MenuModel::getSingleton();
        
        $map = [
            'menu_name' => '内容'
        ];
        $model->where($map)->delete();
        
        $map = [
            'menu_url' => [
                'like',
                '%@module/blog%'
            ]
        ];
        $model->where($map)->delete();
    }
}
