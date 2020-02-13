<?php

namespace thefx\blocks\models\blocks;

use app\behaviors\UploadImageBehavior5;
use app\shop\entities\Image\Image;
use thefx\blocks\models\blocks\queries\BlockItemPropAssignmentsQuery;
use yii\db\ActiveRecord;

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
 * @property BlockPropElem[] $propElements
 * @property BlockPropElem[] $propElementAssign
 */
class BlockItemPropAssignments extends ActiveRecord
{
    protected $imageManager;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function getValue()
    {
        switch ($this->prop->type) {
            case BlockProp::TYPE_STRING:
            case BlockProp::TYPE_TEXT:
            case BlockProp::TYPE_INT:
                return $this->value;
                break;
            case BlockProp::TYPE_FILE:
//                $titles = [];
//                if ($this->prop->multi) {
//                    foreach ($this->propElements as $propElement) {
//                        $titles[] = $propElement->title;
//                    }
//                    return $titles;
//                }
                return explode(';', $this->value);
                break;
            case BlockProp::TYPE_LIST:
                $titles = [];
                if ($this->prop->multi) {
                    $array = explode(';', $this->value);
                    foreach ($this->propElements as $propElement) {
                        if (in_array($propElement->id, $array, false)) {
                            $titles[$propElement->id] = $propElement->title;
                        }
                    }
                    return $titles;
                }
                return $this->propElement->title;
                break;
            case BlockProp::TYPE_RELATIVE_BLOCK_CAT:
                return BlockItem::find()
                    ->with(['propAssignments.prop'])
                    ->where(['parent_id' => $this->value])->all();
                break;
            default:
               return null;
        }
    }

    public function getCode()
    {
        switch ($this->prop->type) {
            case BlockProp::TYPE_LIST:
                $titles = [];
                if ($this->prop->multi) {
                    $array = explode(';', $this->value);
                    foreach ($this->propElements as $propElement) {
                        if (in_array($propElement->id, $array, false)) {
                            $titles[$propElement->id] = $propElement->code;
                        }
                    }
                    return $titles;
                }
                return $this->propElement->code;
                break;
            default:
                return null;
        }
    }

    public function isForProp($propId)
    {
        return $this->prop_id == $propId;
    }

    public function getProp()
    {
        return $this->hasOne(BlockProp::class, ['id' => 'prop_id']);
    }

    public function getPropElement()
    {
        return $this->hasOne(BlockPropElem::class, ['id' => 'value']);
    }

    public function getPropElements()
    {
        return $this->hasMany(BlockPropElem::class, ['block_prop_id' => 'prop_id']);
    }

    public function getPropElementAssign()
    {
        return $this->hasMany(BlockPropElem::class, ['block_prop_id' => 'prop_id', 'id' => 'value']);
    }

    public function getBlockItem()
    {
        return $this->hasOne(BlockItem::class, ['id' => 'block_item_id']);
    }

    public function beforeValidate()
    {
        if ($this->prop->isImage()) {
            $config = [
                'defaultCrop' => [1920, 1200, 'widen'],
                'crop' => [[250,250,'prev','widen']],
                'deleteOldImages' => !$this->prop->isMulti(),
            ];

            /* fix */
            if ($this->prop->code === 'FILE_GEN_PLAN') {
                $config = array_merge($config, [
                    'defaultCrop' => [3000, 0, 'widen'],
                    'crop' => [[550,550,'prev','widen']],
                ]);
            }
            $this->attachBehaviorImageUpload($config);
        }
        return parent::beforeValidate();
    }

    private function attachBehaviorImageUpload($config)
    {
        /** @var Block $block */
        $block = Block::findOne($this->prop->block_id);
        $savePath = $this->prop->upload_path ?: "@app/web/upload/{$block->settings->upload_path}/";
        $watermark = $this->prop->watermark_path ?: $_SERVER['DOCUMENT_ROOT'] . '/upload/watermark20.png';
        if ($watermark === 'null') { $watermark = null; }

        $this->attachBehavior('value_photo', [
            'class' => UploadImageBehavior5::class,
            'attributeName' => 'value',
//            'cropCoordinatesAttrName' => 'value_crop',
            'savePath' => $savePath,
            'generateNewName' => static function () {
                return date('Y_m_d_His') . uniqid('', true);
            },
            'watermark' => $watermark,
            'deleteOldImages' => $config['deleteOldImages'],
            'defaultCrop' => $config['defaultCrop'],
            'crop' => $config['crop']
        ]);
    }

    public function deletePhoto($fileName)
    {
        $this->value = str_replace($fileName, '', $this->value);
        $this->value = str_replace(';;', ';', $this->value);
        $this->value = trim($this->value, ';');

        $this->save(false) or die(var_dump($this->errors));
        (new Image())->removeImage($fileName);
        return $this;
    }

    public function getImagesPath()
    {
        $url = $this->prop->web_path ?: "@web/upload/{$this->getUploadPath()}";
        $images = [];
        foreach ($this->getImages() as $image) {
            $images[$image] = \Yii::getAlias($url) . '/' . $image;
        }
        return $images;
    }

    public function getImages()
    {
        return !is_array($this->getAttribute('value')) ? array_filter(explode(';', $this->getAttribute('value'))) : [];
    }

    public function getUploadPath()
    {
        return $this->prop->block->settings->upload_path;
    }

    ######################

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%block_item_prop_assignments}}';
    }

    /**
     * {@inheritdoc}
     */
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
            $this->prop->isImage() ? ['value', 'file' /*, 'mimeTypes' => 'image/*'*/, 'maxFiles' => 10, 'skipOnEmpty' => true] : false,
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
            'prop_id' => 'Prop ID',
            'value' => 'Value',
        ];
    }

    /**
     * {@inheritdoc}
     * @return BlockItemPropAssignmentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockItemPropAssignmentsQuery(static::class);
    }
}
