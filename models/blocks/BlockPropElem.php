<?php

namespace thefx\blocks\models\blocks;

use thefx\blocks\models\blocks\queries\BlockPropElemQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block_prop_elem}}".
 *
 * @property int $id
 * @property int $block_prop_id
 * @property string $title
 * @property string $code
 * @property int $sort
 * @property int $default
 * @property BlockProp $blockProp
 */
class BlockPropElem extends ActiveRecord
{
    public function isExists($id)
    {
        return $this->id == $id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_prop_elem}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[/*'block_prop_id',*/ 'title', /*'code', 'sort'*/], 'required'],
            [['block_prop_id', 'sort', 'default'], 'integer'],
            [['title', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_prop_id' => 'Block Prop ID',
            'title' => 'Название',
            'code' => 'Код',
            'sort' => 'Сортировка',
            'default' => 'По умолчанию',
        ];
    }

    public function getBlockProp()
    {
        return $this->hasOne(BlockProp::class, ['id' => 'block_prop_id']);
    }

    /**
     * @inheritdoc
     * @return BlockPropElemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockPropElemQuery(static::class);
    }
}
