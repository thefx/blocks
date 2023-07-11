<?php

namespace thefx\blocks\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block_property_elements}}".
 *
 * @property int $id
 * @property int $property_id
 * @property string $title
 * @property string $code
 * @property int $sort
 * @property int $default
 * @property BlockProperty $blockProperty
 */
class BlockPropertyElement extends ActiveRecord
{
    public function isExists($id)
    {
        return $this->id == $id;
    }

    public function getBlockProperty()
    {
        return $this->hasOne(BlockProperty::class, ['id' => 'property_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_property_elements}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[/*'property_id',*/ 'title', /*'code', 'sort'*/], 'required'],
            [['property_id', 'sort', 'default'], 'integer'],
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
            'property_id' => 'Block Prop ID',
            'title' => 'Название',
            'code' => 'Код',
            'sort' => 'Сортировка',
            'default' => '',
        ];
    }
}
