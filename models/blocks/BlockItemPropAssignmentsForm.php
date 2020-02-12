<?php

namespace thefx\blocks\models\blocks;

use app\behaviors\UploadImageBehavior4;
use yii\base\Model;

/**
 * This is the model class for table "{{%block_item_prop_assignments}}".
 *
 * @property int $id
 * @property int $block_item_id
 * @property int $prop_id
 * @property string $value
 * @property BlockProp $prop
 * @property BlockItem $blockItem
 * @property BlockPropElem $propElement
 * @property array $imagesPath
 * @property array $images
 * @property BlockPropElem[] $propElements
 */
class BlockItemPropAssignmentsForm extends Model
{
    public $value;

//    public function isForProp($propId)
//    {
//        return $this->prop_id == $propId;
//    }
//
//    public function getProp()
//    {
//        return $this->hasOne(BlockProp::class, ['id' => 'prop_id']);
//    }
//
//    public function getPropElement()
//    {
//        return $this->hasOne(BlockPropElem::class, ['block_prop_id' => 'prop_id']);
//    }
//
//    public function getPropElements()
//    {
//        return $this->hasMany(BlockPropElem::class, ['block_prop_id' => 'prop_id']);
//    }
//
//    public function getBlockItem()
//    {
//        return $this->hasOne(BlockItem::class, ['id' => 'block_item_id']);
//    }

    public function beforeValidate()
    {
        if ($this->prop->isFile()) {
            $this->attachBehaviorImageUpload();
        }
        return parent::beforeValidate();
    }

    private function attachBehaviorImageUpload()
    {
        $block = Block::findOne($this->prop->block_id);

        $this->attachBehavior('value_photo', [
            'class' => UploadImageBehavior4::class,
            'attributeName' => 'value',
//            'cropCoordinatesAttrName' => 'value_crop',
            'savePath' => "@app/web/upload/{$block->settings->upload_path}/",
            'generateNewName' => function () {
                return date('Y_m_d_His') . uniqid('', false);
            },
            'deleteOldImages' => !$this->prop->isMulti(),
            'defaultCrop' => [1323, 546, 'widen'],
//            'crop' => BlockTranslateHelper::PHOTO_PREVIEW['crop']
        ]);
    }

//    public function deletePhoto($fileName)
//    {
//        $this->value = str_replace($fileName, '', $this->value);
//        $this->value = str_replace(';;', ';', $this->value);
//        $this->value = trim($this->value, ';');
//
//        $this->save(false) or die(var_dump($this->errors));
//        return $this;
//    }

    public function getImagesPath()
    {
        $images = [];
        foreach ($this->getImages() as $image) {
            $images[$image] = \Yii::getAlias("@web/upload/{$this->getUploadPath()}") . '/' . $image;
        }
        return $images;
    }

    public function getImages()
    {
        return !is_array($this->value) ? array_filter(explode(';', $this->value)) : [];
    }

//    public function getUploadPath()
//    {
//        return $this->prop->block->settings->upload_path;
//    }

    ######################

    public function rules()
    {
        return array_filter([
            [['prop_id'], 'required'],
            [['block_item_id', 'prop_id'], 'integer'],
//            [['block_item_id', 'prop_id'], 'unique', 'targetAttribute' => ['block_item_id', 'prop_id']],
            $this->prop->required ? ['value', 'required'] : false,
            $this->prop->isInteger() ? ['value', 'integer'] : false,
            $this->prop->isString() ? ['value', 'string', 'max' => 255] : false,
//            $this->prop->isList() ? ['value', 'string', 'max' => 255] : false,
//            $this->prop->isList() ? ['value', 'integer'] : false,
//            $this->prop->isList() && !$this->prop->isMulti() ? ['value', 'integer'] : false,
//            $this->prop->isList() && $this->prop->isMulti()? ['value', 'each', 'rule' => ['integer']] : false,
            $this->prop->isFile() ? ['value', 'file' /*, 'mimeTypes' => 'image/*'*/, 'maxFiles' => 10, 'skipOnEmpty' => true] : false,
            ['value', 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_item_id' => 'Block Item ID',
            'prop_id' => 'Prop ID',
            'value' => 'Value',
        ];
    }
}
