<?php

namespace thefx\blocks\models;

use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%block_item_property_assignments}}".
 *
 * @property int $id
 * @property int $block_item_id
 * @property int $property_id
 * @property string $value
 * @property BlockProperty $property
 * @property BlockItem $blockItem
 * @property BlockItem $relativeBlockItem
 * @property BlockPropertyElement $propertyElement
 * @property BlockFiles $file
 * @property BlockPropertyElement[] $propertyElements
 * @property ContentPropertyElements[] $propertyElementAssign
 */
class BlockItemPropertyAssignments extends ActiveRecord
{
    protected $imageManager;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function getValue()
    {
        switch ($this->property->type) {
            case BlockProperty::TYPE_STRING:
            case BlockProperty::TYPE_TEXT:
            case BlockProperty::TYPE_INT:
                return $this->value;
            case BlockProperty::TYPE_FILE:
                return $this->file;
            case BlockProperty::TYPE_LIST:
                return $this->propertyElement;
            case BlockProperty::TYPE_RELATIVE_ITEM:
//                return $this->relativeContent;
                return $this->value;
            default:
                return null;
        }
    }

//    public function getCode()
//    {
//        switch ($this->property->type) {
//            case BlockProperty::TYPE_LIST:
//                $titles = [];
//                if ($this->property->multi) {
//                    $array = explode(';', $this->value);
//                    foreach ($this->propertyElements as $propertyElement) {
//                        if (in_array($propertyElement->id, $array, false)) {
//                            $titles[$propertyElement->id] = $propertyElement->code;
//                        }
//                    }
//                    return $titles;
//                }
//                return $this->propertyElement->code;
//            default:
//                return null;
//        }
//    }

    public function isForProperty($propertyId)
    {
        return $this->property_id == $propertyId;
    }

    public function getProperty()
    {
        return $this->hasOne(BlockProperty::class, ['id' => 'property_id']);
    }

    public function getPropertyElement()
    {
        return $this->hasOne(BlockPropertyElement::class, ['id' => 'value']);
    }

    public function getPropertyElements()
    {
        return $this->hasMany(BlockPropertyElement::class, ['property_id' => 'property_id']);
    }

    public function getFile()
    {
        return $this->hasOne(BlockFiles::class, ['id' => 'value']);
    }

    public function getPropertyElementAssigns()
    {
        return $this->hasMany(BlockPropertyElement::class, ['property_id' => 'property_id', 'id' => 'value']);
    }

    public function getBlockItem()
    {
        return $this->hasOne(BlockItem::class, ['id' => 'block_item_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%block_item_property_assignments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_filter([
            [['property_id'], 'required'],
            [['block_item_id', 'property_id'], 'integer'],
            $this->property->required ? ['value', 'required'] : false,
            $this->property->isInteger() ? ['value', 'integer'] : false,
            $this->property->isString() ? ['value', 'string', 'max' => 255] : false,
//            $this->property->isFile() ? ['value', 'each', 'rule' => ['integer']]: false,
//            $this->property->isList() && !$this->property->isMultiple() ? ['value', 'integer']: false,
//            $this->property->isList() && $this->property->isMultiple() ? ['value', 'each', 'rule' => ['integer']]: false,

//            $this->property->isFile() ? ['value', 'string'] : false,
//            $this->property->isImage() ? ['value', 'string'] : false,
//            $this->property->isFile() ? ['value', 'file' /*, 'mimeTypes' => 'image/*', 'maxFiles' => 10*/, 'skipOnEmpty' => true] : false,
            ['value', 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_item_id' => 'Block Item ID',
            'property_id' => 'Prop ID',
            'value' => $this->property->title,
        ];
    }

    /**
     * @throws NotFoundHttpException
     * @return self
     */
    public static function findOrFail($id)
    {
        if (($model = self::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
