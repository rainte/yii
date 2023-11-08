<?php

namespace Rainte\Yii\Web;

use Rainte\Yii\Exceptions\Exception;

abstract class Controller extends \yii\rest\Controller
{
    /**
     * @var string 关联的 AR 模型类.
     */
    public $modelClass = 'change\models';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = true;
        $behaviors['verbs']['class'] = \yii\filters\VerbFilter::class;
        $behaviors['access'] = [
            'class' => \yii\filters\AccessControl::class,
            'denyCallback' => function ($rule, $action) {
                Exception::fail('请登录.', 401);
            },
            'rules' => [[
                'allow' => true,
                'roles' => ['@'],
            ]],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [];
    }
}
