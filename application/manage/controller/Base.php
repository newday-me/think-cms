<?php
namespace app\manage\controller;

use think\Url;
use think\Config;
use think\db\Query;
use cms\Widget;
use cms\Response;
use cms\Controller;
use core\Model;
use core\Validate;
use app\manage\service\LoginService;
use app\manage\service\ViewService;
use app\manage\service\AuthService;
use app\manage\service\MenuService;

class Base extends Controller
{

    /**
     * 网站标题
     *
     * @var unknown
     */
    protected $siteTitle;

    /**
     * 用户ID
     *
     * @var unknown
     */
    protected $userId;

    /**
     * 回退
     *
     * @var string
     */
    const JUMP_BACK = 'jump_back';

    /**
     * 刷新
     *
     * @var unknown
     */
    const JUMP_REFRESH = 'jump_refresh';

    /**
     * 上一页
     *
     * @var unknown
     */
    const JUMP_REFERER = 'jump_referer';

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::_initialize()
     */
    protected function _initialize()
    {
        $publicAction = Config::get('manage_public_action') ?: [
            'manage.start.*'
        ];
        if (! AuthService::getSingleton()->isPublicAction($publicAction)) {
            
            // 验证登录
            $this->verifyLogin();
            
            // 验证权限
            $this->verifyAuth();
            
            // 创建菜单
            $this->isAjaxRequest() || $this->buildMenu();
        }
    }

    /**
     * 验证登录
     *
     * @return void
     */
    protected function verifyLogin()
    {
        $login = LoginService::getSingleton();
        $loginUser = $login->getLoginUser();
        if (empty($loginUser)) {
            Response::getSingleton()->redirect('manage/start/login');
        }
        
        // 用户ID
        $this->userId = $loginUser['user_id'];
        
        // 用户信息
        $manageUser = $login->gteLoginUserInfo();
        $this->assign('manage_user', $manageUser);
        
        // 管理首页
        $this->assign('manage_url', $loginUser['manage_url']);
    }

    /**
     * 验证权限
     *
     * @return void
     */
    protected function verifyAuth()
    {
        if (! AuthService::getSingleton()->isAuthAction()) {
            $this->error('你没有权限访问该页面');
        }
    }

    /**
     * 创建菜单
     *
     * @return void
     */
    protected function buildMenu()
    {
        $menu = MenuService::getSingleton();
        
        // 菜单树
        $menuTree = $menu->getMenuTree();
        $this->assign('main_tree', $menuTree);
        
        // 主菜单
        $mainMenu = $menu->getMainMenu();
        $this->assign('main_menu', $mainMenu);
        
        // 侧边菜单
        $siderMenu = $menu->getSiderMenu();
        $this->assign('sider_menu', $siderMenu);
    }

    /**
     * 列表
     *
     * @param array $list            
     * @param Closure $perform            
     * @return void
     */
    protected function _list($list, \Closure $perform = null)
    {
        if (is_string($list)) {
            $list = $this->buildModel($list)->select();
        } elseif ($list instanceof Model || $list instanceof Query) {
            $list = $list->select();
        }
        
        $perform && $perform($list);
        $this->assign('_list', $list);
    }

    /**
     * 分页列表
     *
     * @param Model $model            
     * @param integer $rowNum            
     * @param Closure $perform            
     * @return void
     */
    protected function _page($model, $rowNum = null, \Closure $perform = null)
    {
        $rowNum || $rowNum = Config::get('manage_row_num');
        $rowNum || $rowNum = 10;
        
        $model = $this->buildModel($model);
        $list = $model->paginate($rowNum);
        $perform && $perform($list);
        
        $this->assign('_list', $list);
        $this->assign('_page', $list->render());
        $this->assign('_total', $list->total());
    }

    /**
     * 赋值记录
     *
     * @param mixed $model            
     * @param integer $id            
     * @return void
     */
    protected function _record($model, $id = null)
    {
        $model = $this->buildModel($model);
        $id || $id = $this->_id();
        
        $record = $model->get($id);
        $this->assign('_record', $record);
    }

    /**
     * 验证数据
     *
     * @param mixed $validate            
     * @param array $data            
     * @return void
     */
    protected function _validate($validate, $data, $scene)
    {
        $validate = $this->buildValidate($validate);
        if (! $validate->scene($scene)->check($data)) {
            $this->error($validate->getError());
        }
    }

    /**
     * 添加记录
     *
     * @param mixed $model            
     * @param array $data            
     * @return void
     */
    protected function _add($model, $data)
    {
        $model = $this->buildModel($model);
        if ($model->save($data)) {
            $this->success('添加成功', self::JUMP_REFERER);
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * 修改记录
     *
     * @param mixed $model            
     * @param array $data            
     * @param array $map            
     * @param string $url            
     * @return void
     */
    protected function _edit($model, $data, $map = null, $url = self::JUMP_REFERER)
    {
        $model = $this->buildModel($model);
        $map || $map = [
            'id' => $this->_id()
        ];
        
        if ($model->save($data, $map)) {
            $this->success('修改成功', $url);
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 更改记录
     *
     * @param mixed $model            
     * @param array $fields            
     * @param string $url            
     * @return void
     */
    protected function _modify($model, $fields = [], $url = self::JUMP_REFRESH)
    {
        $request = $this->getRequest();
        
        // 字段
        $field = $request->param('field');
        if (! in_array($field, $fields)) {
            $this->error('非法的字段');
        }
        
        // 值
        $value = $request->param('value', '');
        
        // 修改
        $map = [
            'id' => $this->_id()
        ];
        $data = [
            $field => $value
        ];
        $model = $this->buildModel($model);
        if ($model->save($data, $map)) {
            $this->success('更改成功', $url);
        } else {
            $this->error('更改失败');
        }
    }

    /**
     * 记录排序
     *
     * @param mixed $model            
     * @param string $field            
     * @param integer $weight            
     * @param mixed $idx            
     *
     * @return void
     */
    protected function _sort($model, $field, $weight = 0, $idx = null)
    {
        if (is_null($idx)) {
            $idx = $this->getRequest()->param('idx', '');
        }
        $idx = is_array($idx) ? $idx : array_filter(explode(',', $idx));
        
        $sort = $weight + count($idx);
        foreach ($idx as $id) {
            $map = [
                'id' => $id
            ];
            $data = [
                $field => $sort
            ];
            $this->buildModel($model)->update($data, $map);
            $sort --;
        }
        
        $this->success('操作成功', self::JUMP_REFRESH);
    }

    /**
     * 删除记录
     *
     * @param mixed $model            
     * @param string $isSoft            
     * @param string $url            
     * @return void
     */
    protected function _delete($model, $isSoft = false, $url = null)
    {
        $map = [
            'id' => $this->_id()
        ];
        $model = $this->buildModel($model);
        $url || $url = self::JUMP_REFRESH;
        
        $status = $isSoft ? $model->softDelete($map) : $model->where($map)->delete();
        if ($status) {
            $this->success('删除成功', $url);
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 恢复记录
     *
     * @param mixed $model            
     * @param string $url            
     * @return void
     */
    protected function _recover($model, $url = null)
    {
        $model = $this->buildModel($model);
        $url || $url = self::JUMP_REFRESH;
        
        $map = [
            'id' => $this->_id()
        ];
        $data = [
            $model->getDeleteTimeField() => 0
        ];
        $status = $model->where($map)->update($data);
        if ($status) {
            $this->success('恢复成功', $url);
        } else {
            $this->error('恢复失败');
        }
    }

    /**
     * ID
     *
     * @return integer
     * @return void
     */
    protected function _id()
    {
        $id = $this->getRequest()->param('id');
        if (is_null($id)) {
            $this->error('ID为空');
        }
        $this->assign('_id', $id);
        return $id;
    }

    /**
     * 构造Model
     *
     * @param mixed $model            
     * @return Model
     */
    protected function buildModel($model)
    {
        if (is_string($model) && class_exists($model)) {
            if (method_exists($model, 'getInstance')) {
                return $model::getInstance();
            } else {
                return new $model();
            }
        }
        return $model;
    }

    /**
     * 构造Validate
     *
     * @param mixed $validate            
     * @return Validate
     */
    protected function buildValidate($validate)
    {
        if (is_string($validate) && class_exists($validate)) {
            if (method_exists($validate, 'getSingleton')) {
                return $validate::getSingleton();
            } elseif (method_exists($validate, 'getInstance')) {
                return $validate::getInstance();
            } else {
                return new $validate();
            }
        }
        return $validate;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::assign()
     */
    protected function assign($name, $value)
    {
        $this->isAjaxRequest() || parent::assign($name, $value);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::beforeViewRender()
     */
    protected function beforeViewRender()
    {
        // 网站标题
        $this->assign('site_title', $this->siteTitle);
        
        // 编辑器
        $manageEditor = Config::get('manage_editor');
        $this->assign('manage_editor', $manageEditor);
        
        // 组件
        $widget = Widget::getSingleton();
        $this->assign('widget', $widget);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::getView()
     */
    protected function getView()
    {
        return ViewService::getSingleton()->getView();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::buildUrl()
     */
    protected function buildUrl($url)
    {
        if ($url == static::JUMP_BACK) {
            return 'javascript:history.go(-1);';
        } elseif ($url == static::JUMP_REFRESH) {
            return 'javascript:history.go(0);';
        } elseif ($url == static::JUMP_REFERER) {
            return 'javascript:location.href = document.referrer;';
        } else {
            return parent::buildUrl($url);
        }
    }
}