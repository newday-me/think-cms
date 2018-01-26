<?php

namespace app\manage\controller;

use think\facade\Env;

class Module extends Base
{
    /**
     *
     * {@inheritdoc}
     *
     * @see Controller::fetch()
     */
    protected function fetch($template = '', $vars = [], $config = [], $renderContent = false)
    {
        if (empty($template)) {
            $moduleViewPath = Env::get('APP_PATH') . 'module/' . _MODULE_ . '/view/';
            $template = $moduleViewPath . \think\Loader::parseName(_CONTROLLER_) . '/' . _ACTION_ . '.html';
        }
        return parent::fetch($template, $vars, $config, $renderContent);
    }
}