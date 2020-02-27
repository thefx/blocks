<?php

namespace thefx\blocks\models\blocks;

use app\behaviors\Slug;
use app\behaviors\UploadImageBehavior5;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use thefx\blocks\behaviours\UploadImageBehavior;
use thefx\blocks\models\blocks\queries\BlockItemQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%block_item}}".
 *
 * @property int $id
 * @property string $title
 * @property string $path
 * @property string $anons
 * @property string $text
 * @property string $photo
 * @property string $photo_preview
 * @property string $date
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property int $block_id
 * @property int $parent_id
 * @property int $public
 * @property int $sort
 * @property int $create_user
 * @property string $create_date
 * @property int $update_user
 * @property string $update_date
 * @property BlockCategory $category
 * @property BlockProp[] $propAll
 * @property BlockItemPropAssignments[] $propAssignments
 * @property BlockItemPropAssignments[] $propAssignmentsIndexed
 * @property BlockItemPropAssignments[] $propAssignmentsFilter
 * @property Block $block
 * @property BlockProp[] $propsIndexed
 *
 * @mixin SaveRelationsBehavior
 */
class BlockItem extends ActiveRecord
{
    public $propAssignmentsTemp;
    public $photo_preview_crop;
    public $photo_crop;

    public function getPhoto($attribute = 'photo')
    {
        return $this->{$attribute} ? '/upload/blocks/' . $this->{$attribute} : '';
    }

    public function getPhotoMobile($attribute = 'photo')
    {
        return file_exists('/upload/blocks/mobile_' . $this->{$attribute}) ? '/upload/blocks/mobile_' . $this->{$attribute} : null;
    }

    public function getPhotoPreview($attribute = 'photo_preview')
    {
        return $this->{$attribute} ? '/upload/blocks/' . $this->{$attribute} : '';
    }

    public function getEditorPath()
    {
        $id = $this->getPrimaryKey();
        $path = 'block';

        $dir = Yii::getAlias('@webroot/upload/' . $path) . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        return Yii::getAlias('@web/upload/' . $path) . '/';
    }

    public function categoryList()
    {
        /** @var BlockCategory $category */
        $category = BlockCategory::findOne(['id' => $this->parent_id]);
        $categories = BlockCategory::find()->where(['block_id' => $category->block_id])->orderBy('lft')->all();

        return ArrayHelper::map($categories, 'id', static function($row) {
            return str_repeat('—', $row->depth) . ' ' . $row->title;
        });
    }

    public function populateAssignments()
    {
        $assignments = [];

        foreach ($this->propAll as $prop) {
            $isset = false;
            foreach ($this->propAssignments as $assignment) {
                if ($assignment->isForProp($prop->id)) {
                    if ($prop->isMulti() && !$prop->isImage() && !$prop->isFile()) {
                        $assignment->value = explode(';', $assignment->value);
                    }
// array
//                    var_dump($prop->isMulti());
//                    if ($prop->isMulti() && !$prop->isFile()) {
//
//                        if (is_array($assignments['_' . $prop->id]->value)) {
//                            $values = $assignments['_' . $prop->id]->value;
//                            $values[] = $assignment->value;
//                        } else {
//
//                            $assignments['_' . $prop->id] = $assignment;
//                            $values[] = $assignment->value;
//                            var_dump($values);
//                        }
//                        $assignments['_' . $prop->id]->value = $values;
//                    } else {
//                        $assignments[] = $assignment;
//                    }
                    $assignments[$prop->id] = $assignment;
                    $isset = true;
                }
            }
            if (!$isset) {
                $newAssignment = new BlockItemPropAssignments([
                    'prop_id' => $prop->id
                ]);
                $newAssignment->populateRelation('prop', $prop);
                $assignments[$prop->id] = $newAssignment;
            }
        }
        $this->propAssignments = $assignments;
//        $this->propAssignmentsTemp = $assignments;
        return $this;
    }

    public function getAssignmentByPropId($propId)
    {
        foreach ($this->propAssignments as $propAssignment) {
            if ($propAssignment->prop->id == $propId) {
                return $propAssignment;
            }
        }
        return null;
    }

//    public function getPropAssignments2($data)
//    {
//        $ass = [];
//        $assignments = $this->propAssignments;
//
//        foreach ($assignments as $i => $model) {
//            $model->setAttributes($data[$model->formName()][$i]);
//            if ($model->prop->isFile()) {
//                $model->value = UploadedFile::getInstances($model, "[$i]value");
//            }
//            $model->validate();
////            if (!$model->prop->isFile() && $model->prop->isMulti()) {
////                foreach ($model->value as $val) {
//////                    $model->value = $val;
////                    $new = clone $model;
////                    $new->value = $val;
////                    array_push($assignments, $new);
////                }
//////                var_dump($model->value);
//////                die;
//////                unset($assignments[$i]);
////            }
//        }
//        $this->propAssignments = array_filter($assignments, function ($data) {
//            return $data->value != '';
//        });
//        return $this;
//    }

    public function loadAssignments($data)
    {
        $assignments = $this->propAssignments;

        foreach ($assignments as $i => $model) {
            $i = $model->prop_id;

            $model->setAttributes($data[$model->formName()][$i]);
            if ($model->prop->isImage() || $model->prop->isFile()) {
                $model->value = UploadedFile::getInstances($model, "[$i]value");
            }
            if (is_array($model->value) && $model->prop->isMulti() && !$model->prop->isImage() && !$model->prop->isFile()) {
                $model->value = array_filter(array_map(static function ($data) {
                    return (int) $data;
                }, $model->value));
                $model->value = implode(';', $model->value);
            }
            $model->validate();
//            if (!$model->prop->isFile() && $model->prop->isMulti()) {
//                foreach ($model->value as $val) {
////                    $model->value = $val;
//                    $new = clone $model;
//                    $new->value = $val;
//                    array_push($assignments, $new);
//                }
////                var_dump($model->value);
////                die;
////                unset($assignments[$i]);
//            }
            $this->on(static::EVENT_AFTER_INSERT,[$this,'createRelCatHandler']);
            $this->on(static::EVENT_AFTER_UPDATE,[$this,'createRelCatHandler']);
        }
        $this->propAssignments = array_filter($assignments, static function ($data) {
            return $data->value != '';
        });
        return $this;
    }

    public function createRelCatHandler()
    {
        $isSave = false;
        foreach ($this->propAll as $i => $prop) {
            if ($prop->type == $prop::TYPE_RELATIVE_BLOCK_CAT && $prop->relative_block_cat != '') {
                $isContinue = false;
                foreach ($this->propAssignments as $propAssignment) {
                    if ($propAssignment->prop_id == $prop->id) { $isContinue = true; break; }
//                    var_dump([$prop->id == $propAssignment->prop_id, $prop->id => $propAssignment->prop_id]);
                }
                if (!$isContinue) {
                    $categoryId = $this->createRelCat($prop->relative_block_cat);
                    $this->addPropAssignment($this->id, $prop->id, $categoryId);
                    $isSave = true;
                }
            }
        }
        if ($isSave) $this->save();
    }

    private function addPropAssignment($block_item_id, $prop_id, $value)
    {
        $assignments = $this->propAssignments;

        $assignments[] = new BlockItemPropAssignments([
            'block_item_id' => $block_item_id,
            'prop_id' => $prop_id,
            'value' => $value,
        ]);

        $this->propAssignments = $assignments;
    }

    private function createRelCat($block_id)
    {
        /** @var Block $block */
        $block = Block::findOne($block_id);

        /** @var BlockCategory $category */
        $category = BlockCategory::find()
            ->where(['block_id' => $block->id])
            ->getRoot()
            ->one();

        $model = new BlockCategory([
            'block_id' => $block->id,
            'parent_id' => $category->id,
            'title' => $this->id . '#' . $this->title,
            'public' => 1,
        ]);

        if ($model->validate()) {
            $model->appendTo($category)->save();
            return $model->id;
        }

        die(var_dump($model->getErrors()));
    }

    public function beforeValidate()
    {
        /** @var BlockCategory $category */
        $category = BlockCategory::findOne($this->parent_id);

        /** @var Block $block */
        $block = Block::findOne($category->block_id);

        $this->attachBehaviors([
            'photo_preview' => [
                'class' => UploadImageBehavior::class,
                    'attributeName' => 'photo_preview',
                    'cropCoordinatesAttrName' => 'photo_preview_crop',
                    'savePath' => "@app/web/upload/{$block->settings->upload_path}/",
                    'generateNewName' => static function () {
                        return date('Y_m_d_His_') . uniqid('', false);
                    },
                    'defaultCrop' => [
                        $block->settings->photo_preview_crop_width,
                        $block->settings->photo_preview_crop_height,
                        $block->settings->photo_preview_crop_type
                    ],
//                    'crop' => [
//                        [300, 300, 'min', 'fit'],
//                    ]
                ],
                'photo' => [
                    'class' => UploadImageBehavior::class,
                    'attributeName' => 'photo',
                    'cropCoordinatesAttrName' => 'photo_crop',
                    'savePath' => "@app/web/upload/{$block->settings->upload_path}/",
                    'generateNewName' => static function () {
                        return date('Y_m_d_His_') . uniqid('', false);
                    },
                    'defaultCrop' => [
                        $block->settings->photo_crop_width,
                        $block->settings->photo_crop_height,
                        $block->settings->photo_crop_type
                    ],
                    // только для поселков
                    'crop' => array_filter($block->id == 12 ? [
                        [640, 1030, 'mobile', 'widen'],
                    ] : [])
                ]
            ]
        );

        return parent::beforeValidate();
    }

    public function getPropLabel($code = null)
    {
        return isset($this->propsIndexed[$code]) ? $this->propsIndexed[$code]->title : null;
    }

    public function getPropValue($code = null)
    {
        foreach ($this->propAssignments as $item) {
            if ($item->prop->code === $code) {
                return $item->getValue();
            }
        }
        return null;
    }

    /**
     * Возвращает код элемента (для типа - список)
     * @param null $code
     * @return BlockItem[]|array|string|null
     */
    public function getPropCodeElem($code = null)
    {
        foreach ($this->propAssignments as $item) {
            if ($item->prop->code == $code) {
                return $item->getCode();
            }
        }
        return null;
    }

    public function getPropValueString($code = null)
    {
        return is_array($this->getPropValue($code)) ? implode(', ', $this->getPropValue($code)) : $this->getPropValue($code);
    }

    public function getPropValueStringLinks($code = null)
    {
        $link = $this->getPropValue($code);
        if (is_array($this->getPropValue($code))) {
            $links = [];
            foreach ($this->getPropValue($code) as $id => $name) {
                $links[] = Html::a($name, ['index', strtolower($code) => [$id]]);
            }
            return implode(' ', $links);
        }

        foreach ($this->propAssignments as $item) {
            if ($item->prop->code === $code) {
                $link = Html::a($item->propElement->title, ['index', strtolower($code) => $item->propElement->id]);
            }
        }
        return $link;
    }

    public function getPropAssignmentValue($code = null)
    {
        foreach ($this->propAssignments as $item) {
            if ($item->prop->code === $code) {
                return $item->value;
            }
        }
        return null;
    }

    public function getPath()
    {
        return  $this->block->path . '/' . $this->path;
    }

    #########################

    public function getBlock()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    public function getPropAll()
    {
        return $this->hasMany(BlockProp::class, ['block_id' => 'block_id']);
    }

    public function getPropsIndexed()
    {
        return $this->hasMany(BlockProp::class, ['block_id' => 'block_id'])->indexBy('code');
    }

    public function getPropAssignments()
    {
        return $this->hasMany(BlockItemPropAssignments::class, ['block_item_id' => 'id']);
    }

    public function getPropAssignmentsIndexed()
    {
        return $this->hasMany(BlockItemPropAssignments::class, ['block_item_id' => 'id'])->indexBy('id');
    }

    public function getPropAssignmentsFilter()
    {
        return $this->hasMany(BlockItemPropAssignments::class, ['block_item_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(BlockCategory::class, ['id' => 'parent_id']);
    }

    #########################

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => Slug::class,
                'in_attribute' => 'title',
                'out_attribute' => 'path'
            ],
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'propAssignments' /*=> ['cascadeDelete' => true]*/
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'parent_id', 'sort'], 'required'],
            [['text'], 'string'],
            [['date', 'create_date', 'update_date'], 'safe'],
            [['parent_id', 'public', 'sort', 'create_user', 'update_user'], 'integer'],
            [['title', 'path', 'anons', 'photo_crop', 'photo_preview_crop', 'seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],
            [['photo', 'photo_preview'], 'file', 'mimeTypes' => 'image/*'],
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
            'title' => 'Название',
            'path' => 'Url',
            'anons' => 'Краткое описание',
            'text' => 'Текст',
            'photo' => 'Фото',
            'photo_preview' => 'Фото для анонса',
            'date' => 'Дата',
            'parent_id' => 'Категория',
            'public' => 'Активность',
            'sort' => 'Сортировка',
            'seo_title' => 'Заголовок в браузере',
            'seo_keywords' => 'Ключевые слова',
            'seo_description' => 'Описание',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Редактировал',
            'update_date' => 'Дата обновления',
        ];
    }

    public function getMetaDescription()
    {
        return $this->seo_description ?: mb_substr(strip_tags($this->title), 0, 255);
    }

    public function getMetaKeywords()
    {
        return $this->seo_keywords;
    }

    /**
     * @param $blockId
     * @param $slug
     * @return BlockItem|array
     */
    public static function getBySlug($blockId, $slug)
    {
        return BlockItem::find()
            ->with(['propAssignments.prop', 'propAssignments.propElements', 'propsIndexed'])
            ->where(['path' => $slug])
            ->andWhere(['block_id' => $blockId])
            ->active()
            ->one();
    }

    /**
     * @inheritdoc
     * @return BlockItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockItemQuery(static::class);
    }
}
