<?php

namespace Rainte\Yii\Web;

class Response
{
    /**
     * @var object 事件对象.
     */
    public $event;
    /**
     * @var callable 匿名函数.
     */
    public $callback;

    /**
     * 响应之前.
     *
     * @return void
     */
    public function beforeSend(): void
    {
        $response = $this->event->sender;
        $response->headers->set('access-control-allow-origin', '*');

        if (
            $response->format == \yii\web\Response::FORMAT_JSON
            || $response->format == \yii\web\Response::FORMAT_XML
        ) {
            if ($response->isOk) {
                $data['code'] = $response->statusCode;
                $data['message'] = $response->statusText;
                is_null($response->data) || $data['data'] = $response->data;
            } else {
                $data['code'] = $response->data['code'];
                $data['message'] = $response->data['message'];
            }

            $response->data = $data;
            $this->callback && call_user_func(
                $this->callback,
                $data['code'],
                $data['message'],
                microtime(true)
            );
        }
    }
}
