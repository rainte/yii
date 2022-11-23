<?php

namespace Rainte\Yii\Exceptions;

use Rainte\Yii\Exceptions\Exception;
use yii\web\Response;

class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function convertExceptionToArray($exception)
    {
        $res = parent::convertExceptionToArray($exception);

        if (!YII_DEBUG && !$exception instanceof Exception) {
            $res['name'] = 'Exception';
            $res['code'] = 500;
            $res['message'] = Response::$httpStatuses[500];
        }

        return $res;
    }
}
