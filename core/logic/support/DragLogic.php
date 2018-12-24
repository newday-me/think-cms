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
     * @return bool|null
     */
    public function drag($mode, $fromNo, $toNo)
    {
        $this->resetError();
        
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
                $this->setError(self::ERROR_CODE_DEFAULT, '未知拖动操作');
                return null;
        }
    }

    /**
     * 拖入
     *
     * @param string $fromNo
     * @param string $toNo
     * @return bool|null
     */
    abstract public function onDragOver($fromNo, $toNo);

    /**
     * 侧边
     *
     * @param bool $before
     * @param string $fromNo
     * @param string $toNo
     * @return bool|null
     */
    abstract public function onDragSide($before, $fromNo, $toNo);

}