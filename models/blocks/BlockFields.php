<?php

namespace thefx\blocks\models\blocks;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
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
 * @property BlockFields[] $children
 * @property string $name [varchar(255)]
 */
class BlockFields extends ActiveRecord
{
    const BLOCK_TYPE_ITEM = 'item';
    const BLOCK_TYPE_CATEGORY = 'category';

    public static function create($name, $block_type): BlockFields
    {
        $model = new self();
        $model->type = 'group';
        $model->value = $name;
        $model->parent_id = 0;
        $model->sort = 0;
        $model->block_type = $block_type;
        return $model;
    }

    public static function createChild($block_id, $type, $value, $sort, $block_type): BlockFields
    {
        $model = new self();
        $model->block_id = $block_id;
        $model->type = (string) $type;
        $model->value = (string) $value;
        $model->sort = $sort;
        $model->block_type = $block_type;
        return $model;
    }

    public function getChildren(): \yii\db\ActiveQuery
    {
        return $this->hasMany(__CLASS__, ['parent_id' => 'id']);
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['children'],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    ###################

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
//            [['id'], 'required'],
            [['id', 'block_id', 'parent_id', 'sort'], 'integer'],
            [['type', 'block_type'], 'string', 'max' => 10],
            [['value'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'parent_id' => 'Parent ID',
            'sort' => 'Sort',
        ];
    }
}
