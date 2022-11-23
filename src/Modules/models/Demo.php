<?php

namespace Rainte\Yii\Modules\models;

class Demo extends \Rainte\Yii\Models\Model
{
    const DOMAIN = 'DEMO';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['id', 'domain', 'name', 'sort', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'tenant_id'], 'required'],
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['sort', 'status'], 'integer'],
            [['id', 'created_by', 'updated_by', 'tenant_id'], 'string', 'max' => 36],
            [['domain', 'name'], 'string', 'max' => 64],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id' => 'id',
            // 'domain' => 'domain',
            'name' => 'name',
            'birthday' => 'birthday',
            'sort' => 'sort',
            'status' => 'status',
            'created_at' => 'created_at',
            // 'created_by' => 'created_by',
            'updated_at' => 'updated_at',
            // 'updated_by' => 'updated_by',
            // 'tenant_id' => 'tenant_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'domain' => '',
            'name' => '',
            'birthday' => '',
            'sort' => '',
            'status' => '',
            'created_at' => '',
            'created_by' => '',
            'updated_at' => '',
            'updated_by' => '',
            'tenant_id' => '',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function map(): array
    {
        return [
            'id' => 'id',
            'domain' => 'domain',
            'username' => 'name',
            'birthday' => 'birthday',
            'sort' => 'sort',
            'status' => 'status',
            'created_at' => 'created_at',
            'created_by' => 'created_by',
            'updated_at' => 'updated_at',
            'updated_by' => 'updated_by',
            'tenant_id' => 'tenant_id',
        ];
    }
}
