<?php
namespace app\blog\custom\exception;

use app\blog\service\ViewService;

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
        return ViewService::getSingleton()->getView();
    }
}