<?php

namespace Rainte\Yii\Web;

use Rainte\Yii\Models\RedisKey;
use Yii;

class User extends \yii\web\User
{
    /**
     * 身份过期时间.
     */
    const EXPIRE = 7200;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->identity->id ?? '';
    }

    /**
     * 得到 TOKEN.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->identity->token ?? '';
    }

    /**
     * 得到租户 ID.
     *
     * @return string
     */
    public function getTenantid()
    {
        return $this->identity->tenant_id ?? '';
    }

    /**
     * @inheritdoc
     */
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        $redis = Yii::$app->redis;

        $key = RedisKey::session($identity->token);
        foreach ($identity as $field => $value) {
            $redis->hmset($key, $field, $value);
        }
        $redis->expire($key, static::EXPIRE);

        parent::afterLogin($identity, $cookieBased, $duration);
    }

    /**
     * @inheritdoc
     */
    protected function afterLogout($identity)
    {
        $redis = Yii::$app->redis;
        $redis->del(RedisKey::session($identity->token));

        parent::afterLogout($identity);
    }
}
