<?php
namespace app\manage\controller;

use think\Url;
use think\Config;
use cms\Widget;
use cms\Response;
use cms\Controller;
use core\Model;
use core\Validate;
use core\manage\logic\MenuLogic;
use app\manage\logic\LoginLogic;
use app\manage\logic\ViewLogic;
use app\manage\logic\AuthLogic;

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
        if (! AuthLogic::getSingleton()->isPublicAction($publicAction)) {
            
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
        $loginLogic = LoginLogic::getSingleton();
        $loginUser = $loginLogic->getLoginUser();
        if (empty($loginUser)) {
            Response::getSingleton()->redirect('manage/start/login');
        }
        
        // 用户ID
        $this->userId = $loginUser['user_id'];
        
        // 用户信息
        $manageUser = $loginLogic->gteLoginUserInfo();
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
        if (! AuthLogic::getSingleton()->isAuthAction($this->userId)) {
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
        $menuLogic = MenuLogic::getSingleton();
        
        // 主菜单
        $mainMenu = $menuLogic->getMainMenu($this->userId);
        $this->assign('main_menu', $mainMenu);
        
        // 侧边菜单
        $siderMenu = $menuLogic->getSiderMenu($this->userId);
        $this->assign('sider_menu', $siderMenu);
    }

    /**
     * 列表
     *
     * @param array $list            
     * @param Closure $perform            
     * @return void
     */
    protected function _list($list, $perform = null)
    {
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
    protected function _page($model, $rowNum = null, $perform = null)
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
        if ($model->create($data)) {
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
     * @return void
     */
    protected function _edit($model, $data, $map = null)
    {
        $model = $this->buildModel($model);
        $map || $map = [
            'id' => $this->_id()
        ];
        
        if ($model->save($data, $map)) {
            $this->success('修改成功', self::JUMP_REFERER);
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
        return $this->buildObject($model);
    }

    /**
     * 构造Validate
     *
     * @param mixed $validate            
     * @return Validate
     */
    protected function buildValidate($validate)
    {
        return $this->buildObject($validate);
    }

    /**
     * 构造对象
     *
     * @param mixed $object            
     * @return object
     */
    protected function buildObject($object)
    {
        if (is_string($object) && class_exists($object)) {
            if (method_exists($object, 'getSingleton')) {
                return $object::getSingleton();
            } elseif (method_exists($object, 'getInstance')) {
                return $object::getInstance();
            } else {
                return new $object();
            }
        }
        return $object;
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
        return ViewLogic::getSingleton()->getView();
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