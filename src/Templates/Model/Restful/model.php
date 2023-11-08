<?php

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db') : ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
    <?php foreach ($labels as $name => $label) : ?>
        <?= "'$name' => '$name',\n" ?>
    <?php endforeach; ?>
    ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
    <?php foreach ($properties as $name => $value) : ?>
        <?= "'$name' => '{$value['comment']}',\n" ?>
    <?php endforeach; ?>
    ];
    }

    /**
     * @inheritdoc
     */
    protected function map(): array
    {
        return [
    <?php foreach ($labels as $name => $label) : ?>
        <?= "'$name' => '$name',\n" ?>
    <?php endforeach; ?>
    ];
    }
}
