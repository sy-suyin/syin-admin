<?php
namespace app\common\library;

class RuntimeError extends \RuntimeException
{
    protected $error;
    protected $code;

    /** 
     * 初始化
     * 
     * @param mixed $error 错误消息
     * @param int $code 状态码
     */
    public function __construct($error, $code=500)
    {
        $this->error   = $error;
        $this->code   = $code;
        $this->message = is_array($error) ? implode("\n\r", $error) : $error;
    }

    /**
     * 获取验证错误信息
     * @access public
     * @return array|string
     */
    public function getErrorMsg()
    {
        return $this->error;
    }

    /**
     * 获取异常状态码
     * @access public
     * @return array|string
     */
    public function getStatusCode()
    {
        return $this->code;
    }
}
