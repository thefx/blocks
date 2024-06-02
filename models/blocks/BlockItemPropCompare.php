<?php

namespace thefx\blocks\models\blocks;

use Yii;

/**
 * This is the model class for table "block_item_prop_compare".
 *
 * @property int $item_id
 * @property int $prop_id
 */
class BlockItemPropCompare extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'block_item_prop_compare';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'prop_id'], 'required'],
            [['item_id', 'prop_id'], 'integer'],
            [['item_id', 'prop_id'], 'unique', 'targetAttribute' => ['item_id', 'prop_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'prop_id' => 'Prop ID',
        ];
    }
}
