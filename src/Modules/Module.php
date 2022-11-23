<?php

namespace Rainte\Yii\Modules;

class Module extends \yii\base\Module
{
    /**
     * @var string 默认控制器.
     */
    public $controllerNamespace = 'Rainte\Yii\Modules\controllers';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => \yii\filters\AccessControl::class,
            'denyCallback' => function ($rule, $action) {
                \app\components\core\Exception::error('请求失败.');
            },
            'rules' => [
                [
                    'allow' => true,
                    'ips' => ['127.0.0.1'],
                    'verbs' => ['GET'],
                ],
                [
                    'allow' => true,
                    'ips' => ['*'],
                    // 'verbs' => ['POST'],
                    'verbs' => ['POST', 'GET'],
                ],
            ],
        ];
        return $behaviors;
    }
}
