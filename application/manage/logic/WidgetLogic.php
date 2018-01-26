<?php

namespace app\manage\logic;

use core\base\Logic;
use app\manage\widget\Widget;

class WidgetLogic extends Logic
{

    /**
     * 获取组件
     *
     * @return Widget
     */
    public function getWidget()
    {
        return Widget::getSingleton();
    }

}