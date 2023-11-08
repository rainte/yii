<?php

namespace Rainte\Yii\Models;

use Yii;

class ChangeLog extends \yii\db\ActiveRecord
{
    const DOMAIN = 'CHANGE_LOG';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%change_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_key'], 'integer'],
            [['time'], 'number'],
            [['request'], 'required'],
            [['request'], 'string'],
            [['created_at'], 'safe'],
            [['app_session', 'app_method', 'app_ver', 'user_id', 'ip'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 32],
            [['message', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'app_key' => 'app_key',
            'app_session' => 'app_session',
            'app_method' => 'app_method',
            'app_ver' => 'app_ver',
            'user_id' => 'user_id',
            'ip' => 'ip',
            'time' => 'time',
            'code' => 'code',
            'message' => 'message',
            'url' => 'url',
            'request' => 'request',
            'created_at' => 'created_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_key' => 'app_key',
            'app_session' => 'app_session',
            'app_method' => 'app_method',
            'app_ver' => 'app_ver',
            'user_id' => 'user_id',
            'ip' => 'ip',
            'time' => 'time',
            'code' => 'code',
            'message' => 'message',
            'url' => 'url',
            'request' => 'request',
            'created_at' => 'created_at',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function map(): array
    {
        return [
            'id' => 'id',
            'app_key' => 'app_key',
            'app_session' => 'app_session',
            'app_method' => 'app_method',
            'app_ver' => 'app_ver',
            'user_id' => 'user_id',
            'ip' => 'ip',
            'time' => 'time',
            'code' => 'code',
            'message' => 'message',
            'url' => 'url',
            'request' => 'request',
            'created_at' => 'created_at',
        ];
    }

    /**
     * 记录日志.
     * 
     * @param int $code 响应编号.
     * @param string $message 响应信息.
     * @param float $microtime 时间.
     * @return void
     */
    public static function add(
        int $code,
        string $message,
        float $microtime
    ): void {
        $model = new static;
        $model->app_key = $_POST['app_key'] ?? 0;
        $model->app_session = $_POST['session'] ?? '';
        $model->app_method = $_POST['method'] ?? '';
        $model->app_ver = $_POST['ver'] ?? '';
        $model->user_id = Yii::$app->user->id;
        $model->ip = Yii::$app->request->userIP;
        $model->time = bcsub(microtime(true), $microtime, 3);
        $model->code = (string)$code;
        $model->message = $message;
        $model->url = Yii::$app->request->hostInfo . Yii::$app->request->url;
        $model->request = serialize(file_get_contents('php://input'));
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();
    }

    /**
     * 解析日志.
     * 
     * @param int $id ID.
     * @return mixed
     */
    public static function parse(int $id)
    {
        $model = static::findOne(['id' => $id]);
        $request = unserialize($model->request);
        if ($model->app_key === 0) {
            return $request;
        } else {
            $res = '';
            $data = explode('&', $request);
            foreach ($data as $item) {
                $kv = explode('=', $item);
                if (isset($kv[1])) {
                    $kv[0] = urldecode($kv[0]);
                    $kv[1] = urldecode($kv[1]);
                    $res .= $kv[0] . ': ' . $kv[1] . PHP_EOL;
                } else {
                    $res .= $item;
                }
            }

            return $res;
        }
    }
}
