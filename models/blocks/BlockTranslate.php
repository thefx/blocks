<?php

namespace thefx\blocks\models\blocks;

use thefx\blocks\models\blocks\queries\BlockTranslateQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block_translate}}".
 *
 * @property int $id
 * @property int $block_id
 * @property string $category
 * @property string $categories
 * @property string $block_item
 * @property string $blocks_item
 * @property string $block_create
 * @property string $block_update
 * @property string $block_delete
 * @property string $category_create
 * @property string $category_update
 * @property string $category_delete
 * @property Block $block
 */
class BlockTranslate extends ActiveRecord
{
    public static function create()
    {
        $model = new self();
        $model->category        = 'Группа';
        $model->categories      = 'Группы';
        $model->block_item      = 'Новость';
        $model->blocks_item     = 'Новости';
        $model->block_create    = 'Добавить новость';
        $model->block_update    = 'Изменить новость';
        $model->block_delete    = 'Удалить новость';
        $model->category_create = 'Добавить группу';
        $model->category_update = 'Изменить группу';
        $model->category_delete = 'Удалить группу';
        return $model;
    }

    public function getBlock()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_translate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['block_id'], 'required'],
            [['block_id'], 'integer'],
            [['category', 'categories', 'block_item', 'blocks_item', 'block_create', 'block_update', 'block_delete', 'category_create', 'category_update', 'category_delete'], 'string', 'max' => 255],
            [['block_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_id' => 'Блок',
            'category' => 'Категория',
            'categories' => 'Категории',
            'block_item' => 'Блок',
            'blocks_item' => 'Блоки',
            'block_create' => 'Добавить блок',
            'block_update' => 'Редактировать блок',
            'block_delete' => 'Удалить блок',
            'category_create' => 'Добавить категорию',
            'category_update' => 'Редактировать категорию',
            'category_delete' => 'Удалить категорию',
        ];
    }

    /**
     * @inheritdoc
     * @return BlockTranslateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockTranslateQuery(static::class);
    }
}
