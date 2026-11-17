<?php

namespace Rainte\Yii\Web;

use Rainte\Yii\Models\RedisKey;
use Rainte\Yii\Web\User;
use Yii;
use yii\web\IdentityInterface;

class UserIdentity extends RateLimiter implements IdentityInterface
{
    /**
     * @var string 会员ID.
     */
    public $id = '';
    /**
     * @var string TOKEN.
     */
    public $token = '';
    /**
     * @var string 租户ID.
     */
    public $tenant_id = '';

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $redis = Yii::$app->redis;
        $key = RedisKey::session($token);

        if ($redis->exists($key)) {
            $redis->expire($key, User::EXPIRE);

            $session = $redis->hmget($key, 'id', 'token', 'tenant_id');

            $identity = new static;
            $identity->id = $session[0];
            $identity->token = $session[1];
            $identity->tenant_id = $session[2];
            return $identity;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($token)
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
    }
}
