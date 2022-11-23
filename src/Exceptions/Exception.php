<?php

namespace Rainte\Yii\Exceptions;

class Exception extends \yii\web\HttpException
{
    /**
     * 异常信息: 默认错误信息.
     */
    const FAIL = '操作失败.';

    /**
     * 失败.
     * 
     * @param string $message 错误信息.
     * @param int $code 错误编码.
     * @param int $status 状态编码.
     * @return void
     */
    public static function fail(
        string $message,
        int $code = 400,
        int $status = 400
    ): void {
        throw new static($status, $message, $code, null);
    }
}
