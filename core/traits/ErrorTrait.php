<?php

namespace core\traits;

trait ErrorTrait
{
    /**
     * 初始化
     *
     * @var bool
     */
    protected $_errorInit = false;

    /**
     * 错误码
     *
     * @var integer
     */
    protected $_errorCode;

    /**
     * 错误信息
     *
     * @var string
     */
    protected $_errorInfo;

    /**
     * 下级错误
     *
     * @var array
     */
    protected $_errorLower;

    /**
     * 错误映射
     *
     * @var array
     */
    protected $_errorMapping = [];

    /**
     * 初始化错误
     */
    public function initError()
    {
        $this->resetError();
    }

    /**
     * 验证错误
     *
     * @return bool
     */
    public function checkError()
    {
        if (is_null($this->getErrorCode())) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取错误码
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getErrorInfo()
    {
        return $this->_errorInfo;
    }

    /**
     * 获取错误
     *
     * @return array
     */
    public function getError()
    {
        return [
            'code' => $this->getErrorCode(),
            'info' => $this->getErrorInfo(),
            'class' => static::class,
            'lower' => $this->_errorLower
        ];
    }

    /**
     * 获取原始错误
     *
     * @param array $error
     * @return array|null
     */
    public function getErrorOrigin($error = null)
    {
        if (is_null($error)) {
            $error = $this->getError();
        }

        if ($error['lower']) {
            return $this->getErrorOrigin($error['lower']);
        } else {
            return $error;
        }
    }

    /**
     * 设置错误
     *
     * @param integer $code
     * @param string $info
     */
    public function setError($code, $info = null)
    {
        if ($this->_errorInit === false) {
            $this->_errorInit = true;
            $this->initError();
        }

        $this->_errorCode = $code;
        if (is_null($info)) {
            $this->_errorInfo = isset($this->_errorMapping[$code]) ? $this->_errorMapping[$code] : '';
        } else {
            $this->_errorInfo = $info;
        }
    }

    /**
     * 重设错误
     */
    public function resetError()
    {
        $this->_errorCode = null;
        $this->_errorInfo = null;
        $this->_errorLower = null;
    }

    /**
     * 根据其它对象设置错误
     *
     * @param mixed $object
     */
    public function setErrorByObject($object)
    {
        $method = 'getError';
        if (is_object($object) && method_exists($object, $method)) {
            $this->setError($this->_errorLower['code'], $this->_errorLower['info']);
            $this->_errorLower = $object->$method();
        }
    }

}