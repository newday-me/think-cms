<?php

namespace core\logic\support;

use core\base\Logic;

abstract class DragLogic extends Logic
{

    /**
     * 拖动
     *
     * @param string $mode
     * @param string $fromNo
     * @param string $toNo
     * @return \cms\core\objects\ReturnObject
     */
    public function drag($mode, $fromNo, $toNo)
    {
        switch ($mode) {
            case 'over':
                return $this->onDragOver($fromNo, $toNo);
                break;
            case 'before':
                return $this->onDragSide(true, $fromNo, $toNo);
                break;
            case 'after':
                return $this->onDragSide(false, $fromNo, $toNo);
                break;
            default:
                return $this->returnError('未知拖动操作');
        }
    }

    /**
     * 拖入
     *
     * @param string $fromNo
     * @param string $toNo
     * @return \cms\core\objects\ReturnObject
     */
    abstract public function onDragOver($fromNo, $toNo);

    /**
     * 侧边
     *
     * @param bool $before
     * @param string $fromNo
     * @param string $toNo
     * @return \cms\core\objects\ReturnObject
     */
    abstract public function onDragSide($before, $fromNo, $toNo);

}