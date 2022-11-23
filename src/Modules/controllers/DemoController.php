<?php

namespace Rainte\Yii\Modules\controllers;

use Rainte\Yii\Exceptions\Exception;
use Rainte\Yii\Modules\models\Demo;
use Rainte\Yii\Web\Controller;
use Yii;

class DemoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'][] = [
            'actions' => ['run'],
            'allow' => true,
            'roles' => ['?'],
        ];
        return $behaviors;
    }

    /**
     * @api {post} www.dev.com 获取信息[/demo/run]
     * @apiVersion 1.0.0
     * @apiName demo-get
     * @apiGroup demo
     * @apiUse Request
     * @apiUse Response
     *
     * @apiParam (BusinessParam) {String} id ID.
     *
     * @apiParamExample {json} 请求示例
     * json:{}
     */
    public function actionRun()
    {
        $post = Yii::$app->request->post();
        $model = Demo::findOne(['id' => $post['id'] ?? '']);
        $model or Exception::fail('数据不存在.');
        return $model->toArray();
    }
}
