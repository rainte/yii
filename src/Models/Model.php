<?php

namespace Rainte\Yii\Models;

use Rainte\Yii\Models\ActiveRecord;

abstract class Model extends ActiveRecord
{
    /**
     * 字段布尔值: TRUE.
     */
    const BOOL_TRUE = '1';
    /**
     * 字段布尔值: FALSE.
     */
    const BOOL_FALSE = '0';

    /**
     * 得到错误信息.
     *
     * @return string
     */
    public function getError(): string
    {
        $errors = array_values($this->firstErrors);
        return reset($errors);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[array_keys($this->toArray() ?? []), 'safe']];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return $this->map();
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $res = [];
        $map = $this->map();
        $formName === null || $data = $data[$formName];

        foreach ($data as $key => $value) {
            $column = $map[$key] ?? $key;

            is_null($value) || $res[$column] = $value;
        }

        return parent::load($res, '');
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        $name = $this->mapName($name);

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        $name = $this->mapName($name);

        if (
            strpos($name, 'prop') === 0
            && is_int(substr($name, 4))
        ) {
            $value = (string) $value;
        }

        parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function __isset($name)
    {
        $name = $this->mapName($name);

        parent::__isset($name);
    }

    /**
     * @inheritdoc
     */
    public function __unset($name)
    {
        $name = $this->mapName($name);

        parent::__unset($name);
    }

    /**
     * 字段映射.
     *
     * @return string
     */
    protected function mapName(string $name): string
    {
        $columns = $this->map();

        if (array_key_exists($name, $columns)) {
            $name = $columns[$name];
        }

        return $name;
    }

    /**
     * 字段映射.
     *
     * @return array
     */
    protected function map(): array
    {
        $attributes = $this->attributes();

        return array_combine($attributes, $attributes);
    }
}
