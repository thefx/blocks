<?php

namespace thefx\blocks\models\blocks;

use thefx\blocks\models\blocks\queries\BlockSeoQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block_seo}}".
 *
 * @property int $id
 * @property int $block_id
 * @property string $item_title
 * @property string $item_keywords
 * @property string $item_description
 * @property string $category_title
 * @property string $category_keywords
 * @property string $category_description
 */
class BlockSeo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%block_seo}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['block_id'], 'required'],
            [['block_id'], 'integer'],
            [['item_title', 'item_keywords', 'item_description', 'category_title', 'category_keywords', 'category_description'], 'string', 'max' => 255],
            [['block_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_id' => 'Block ID',
            'item_title' => 'Item Title',
            'item_keywords' => 'Item Keywords',
            'item_description' => 'Item Description',
            'category_title' => 'Category Title',
            'category_keywords' => 'Category Keywords',
            'category_description' => 'Category Description',
        ];
    }

    /**
     * {@inheritdoc}
     * @return BlockSeoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockSeoQuery(static::class);
    }
}
