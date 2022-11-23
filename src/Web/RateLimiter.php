<?php

namespace Rainte\Yii\Web;

use Yii;
use yii\filters\RateLimitInterface;

class RateLimiter implements RateLimitInterface
{
    /**
     * @var int 速率限制.
     */
    protected $rateLimit = 10;
    /**
     * @var int 过期时间.
     */
    protected $expire = 600;

    /**
     * @inheritdoc
     */
    public function getRateLimit($request, $action)
    {
        return [$this->rateLimit, 1]; // $rateLimit requests per second.
    }

    /**
     * @inheritdoc
     */
    public function loadAllowance($request, $action)
    {
        $redis = Yii::$app->redis;
        $key = $this->cacheKey();

        $allowance = $redis->hmget($key, 'allowance', 'timestamp');

        if (empty($allowance)) {
            return [$this->rateLimit, time()];
        }

        return $allowance;
    }

    /**
     * @inheritdoc
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $redis = Yii::$app->redis;
        $key = $this->cacheKey();

        $datetime = date('Y-m-d H:i:s', $timestamp);

        $redis->hmset($key, 'allowance', $allowance);
        $redis->hmset($key, 'timestamp', $timestamp);
        $redis->hmset($key, 'datetime', $datetime);
        $redis->expire($key, $this->expire);
    }

    /**
     * 得到缓存键名.
     *
     * @return string
     */
    protected function cacheKey(): string
    {
        return 'ratelimit:' . md5(Yii::$app->request->userIP);
    }
}
