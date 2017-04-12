<?php
namespace app\manage\custom\exception;

use app\manage\service\ViewService;

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