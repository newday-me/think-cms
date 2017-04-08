<?php
namespace app\manage\custom\exception;

use app\manage\logic\ViewLogic;

class Handle extends \cms\exception\Handle
{

    /**
     *
     * {@inheritdoc}
     *
     * @see Handle::getView()
     */
    protected function getView()
    {
        return ViewLogic::getSingleton()->getView();
    }

}