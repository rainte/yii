<?php

namespace Rainte\Yii\Models;

use Rainte\Yii\Exceptions\Exception;
use Rainte\Yii\Models\SoftDeleteBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;

abstract class ActiveRecord extends BaseActiveRecord
{
    /**
     * 业务.
     */
    const DOMAIN = '';

    /**
     * 记录状态值: 激活的.
     */
    const STATUS_ON = 1;
    /**
     * 记录状态值: 冻结的.
     */
    const STATUS_OFF = 0;
    /**
     * 记录状态值: 删除的.
     */
    const STATUS_DEL = -1;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [[
            'class' => AttributeBehavior::class,
            'attributes' => [
                static::EVENT_BEFORE_INSERT => [
                    'created_at',
                    'updated_at'
                ],
                static::EVENT_BEFORE_UPDATE => ['updated_at'],
                static::EVENT_BEFORE_DELETE => ['updated_at'],
            ],
            'value' => date('Y-m-d H:i:s'),
        ], [
            'class' => AttributeBehavior::class,
            'attributes' => [
                static::EVENT_BEFORE_INSERT => [
                    'created_by',
                    'updated_by'
                ],
                static::EVENT_BEFORE_UPDATE => ['updated_by'],
                static::EVENT_BEFORE_DELETE => ['updated_by'],
            ],
            'value' => function ($event) {
                return Yii::$app->user->id;
            },
        ], [
            'class' => AttributeBehavior::class,
            'attributes' => [
                static::EVENT_BEFORE_INSERT => 'domain',
            ],
            'value' => static::DOMAIN,
        ], [
            'class' => AttributeBehavior::class,
            'attributes' => [
                static::EVENT_BEFORE_INSERT => 'tenant_id',
            ],
            'value' => function ($event) {
                return Yii::$app->user->tenantid;
            },
        ], 'softDeleteBehavior' => [
            'class' => SoftDeleteBehavior::class,
            'softDeleteAttributeValues' => [
                'status' => static::STATUS_DEL,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Yii::$app->user->id,
            ],
            // mutate native `delete()` method.
            'replaceRegularDelete' => true,
        ]];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return static::findAlias();
    }

    /**
     * 查询并赋值表别名.
     *
     * @param string $alias 表别名.
     * @param bool $softDeleteWhere 是否加条件.
     * @return ActiveQuery
     */
    public static function findAlias(
        string $alias = '',
        bool $softDelete = true
    ) {
        $query = parent::find();

        $alias and $query->alias($alias);

        $softDelete && $query->andWhere(static::andSoftWhere($alias));

        return $query;
    }

    /**
     * 批量添加.
     *
     * @param array $data 数据.
     * @return int
     */
    public static function batchInsert(array $data): int
    {
        if ($data) {
            $rows = [];
            $now = date('Y-m-d H:i:s');

            $model = new static();
            foreach ($data as &$item) {
                $row = new static();
                $row->load($item);
                $row->loadDefaultValues($item);
                $row->domain = static::DOMAIN;
                $row->created_by = Yii::$app->user->id;
                $row->updated_by = Yii::$app->user->id;
                $row->create_time = $now;
                $row->operate_time = $now;
                $row->tenant_id = Yii::$app->user->tenantid;
                $row->validate() or Exception::fail($row->error);
                $rows[] = $row;
            }

            $columns = $model->attributes();

            return Yii::$app->db->createCommand()
                ->batchInsert(static::tableName(), $columns, $rows)
                ->execute();
        }

        return 0;
    }

    /**
     * @inheritdoc
     */
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        $attributes['updated_by'] = Yii::$app->user->id;

        $condition = ['AND', $condition, static::andSoftWhere()];

        return parent::updateAll($attributes, $condition, $params);
    }

    /**
     * @inheritdoc
     */
    public static function deleteAll($condition = null, $params = [])
    {
        $attributes = ['status' => static::STATUS_DEL];

        return static::updateAll($attributes, $condition, $params);
    }

    /**
     * @inheritdoc
     */
    public static function updateAllCounters(
        $counters,
        $condition = '',
        $params = []
    ) {
        $condition = ['AND', $condition, static::andSoftWhere()];

        return parent::updateAllCounters($counters, $condition, $params);
    }

    /**
     * 追加软删除条件.
     *
     * @param string $alias 别名.
     * @return array
     */
    protected static function andSoftWhere(string $alias = ''): array
    {
        $alias .= $alias ? '.' : '';

        return [
            'AND',
            [$alias . 'domain' => static::DOMAIN],
            ['<>', $alias . 'status', static::STATUS_DEL],
            [$alias . 'tenant_id' => Yii::$app->user->tenantid],
        ];
    }
}
