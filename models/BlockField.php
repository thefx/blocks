<?php

namespace thefx\blocks\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block_fields}}".
 *
 * @property int $id
 * @property int $block_id
 * @property string $type
 * @property string $block_type
 * @property string $value
 * @property int $parent_id
 * @property int $sort
 * @property BlockField[] $children
 * @property BlockProperty $property
 * @property string $name [varchar(255)]
 */
class BlockField extends ActiveRecord
{
    const TYPE_BLOCK_ITEM = 'item';
    const TYPE_BLOCK_CATEGORY = 'category';

    const TYPE_MODEL = 'model';
    const TYPE_PROP = 'prop';
    const TYPE_GROUP = 'group';

    public static function createGroup($block_id, $block_type, $name, $sort): BlockField
    {
        $model = new self();
        $model->block_id = $block_id;
        $model->type = self::TYPE_GROUP;
        $model->value = $name;
        $model->sort = $sort;
        $model->block_type = $block_type;
        $model->parent_id = 0;
        return $model;
    }

    public function getChildren(): \yii\db\ActiveQuery
    {
        return $this->hasMany(__CLASS__, ['parent_id' => 'id']);
    }

    public function getProperty(): \yii\db\ActiveQuery
    {
        return $this->hasOne(BlockProperty::class, ['id' => 'value']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%block_fields}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['block_id', 'parent_id', 'sort'], 'integer'],
            [['type', 'block_type'], 'string', 'max' => 10],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'block_id' => 'Block ID',
            'type' => 'Type',
            'value' => 'Value',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'sort' => 'Sort',
        ];
    }
}
