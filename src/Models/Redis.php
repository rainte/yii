<?php

namespace Rainte\Yii\Models;

class Redis extends \yii\redis\Connection
{
    /**
     * REDIS 根键名.
     */
    const BASE = '';
    /**
     * REDIS 键名连接符.
     */
    const GLUE = ':';

    /**
     * 得到键.
     *
     * @param array $args 参数.
     * @return string
     */
    public static function key(...$args): string
    {
        array_unshift($args, static::BASE);
        return join(static::GLUE, $args);
    }
}
