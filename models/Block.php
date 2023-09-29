<?php

namespace thefx\blocks\models;

use thefx\blocks\behaviors\SlugBehavior;
use thefx\user\models\User;
use yii\behaviors\AttributesBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%block}}".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property int $create_user
 * @property string $create_date
 * @property int $update_user
 * @property string $update_date
 * @property BlockSection $category
 * @property BlockProperty[] $properties
 * @property BlockTranslate $translate
 * @property BlockField[] $fields
 * @property array $fieldsTemplates
 * @property array $defaultFieldsCategoryTemplates
 * @property array $fieldsCategoryTemplates
 * @property array $defaultFieldsTemplates
 * @property BlockField[] $fieldsCategory
 * @property User $createUser
 * @property User $updateUser
 * @property int $sort [int(10)]
 */
class Block extends ActiveRecord
{
    public static function create()
    {
        $model = new self();
        $model->sort  = 100;

        return $model;
    }

//    /**
//     * @param $code
//     * @return BlockProperty|null
//     */
//    public function getPropByCode($code)
//    {
//        foreach ($this->props as $prop) {
//            if ($prop->code === $code) {
//                return $prop;
//            }
//        }
//        return null;
//    }

    public function getFieldsTemplates($withHint = false)
    {
        if ($this->fields) {
            $arr = [];
            $blockItemModel = new BlockItem();
            foreach ($this->fields as $field) {
                if ($field->parent_id == 0) {
                    $children = [];
                    foreach ($field->children as $item) {
                        if ($withHint) {
                            switch ($item->type) {
                                case BlockField::TYPE_PROP:
                                    $hint = ($item->property->isRequired() ? '~' : '') . $item->property->title;
                                    break;
                                case BlockField::TYPE_MODEL:
                                    $hint = $blockItemModel->getAttributeLabel($item->value);
                                    break;
                            }
                        }

                        $children[] = [
                            'type' => $item->type,
                            'value' => $item->value,
                            'name' => $item->name ?? "",
                            'hint' => $hint ?? null,
                        ];
                    }
                    $arr[$field->value] = $children;
                }
            }
            return $arr;
        }
        return $this->getDefaultFieldsTemplates();
    }

    public function getDefaultFieldsTemplates()
    {
        $propsRows = [];
        $blModel = new BlockItem();

        /** @var BlockProperty $prop */
        foreach ($this->properties as $prop) {
            $propsRows[] = ['type' => 'prop', 'value' => $prop->id, 'hint' => ($prop->isRequired() ? '~' : '') . $prop->title];
        }

        return [
            'Краткая информация' => [
                [ 'type' => 'model', 'name' => "", 'value' => 'title', 'hint' => $blModel->getAttributeLabel('title') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'alias', 'hint' => $blModel->getAttributeLabel('alias') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'date', 'hint' => $blModel->getAttributeLabel('date') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'anons', 'hint' => $blModel->getAttributeLabel('anons') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'photo_preview', 'hint' => $blModel->getAttributeLabel('photo_preview') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'section_id', 'hint' => $blModel->getAttributeLabel('section_id') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'public', 'hint' => $blModel->getAttributeLabel('public') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'sort', 'hint' => $blModel->getAttributeLabel('sort') ],
            ],
            'Подробная информация' => [
                [ 'type' => 'model', 'name' => "", 'value' => 'photo', 'hint' => $blModel->getAttributeLabel('photo') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'text', 'hint' => $blModel->getAttributeLabel('text') ],
            ],
            'Характеристики' => $propsRows,
            'Сео' => [
                [ 'type' => 'model', 'name' => "", 'value' => 'seo_title', 'hint' => $blModel->getAttributeLabel('seo_title') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'seo_keywords', 'hint' => $blModel->getAttributeLabel('seo_keywords') ],
                [ 'type' => 'model', 'name' => "", 'value' => 'seo_description', 'hint' => $blModel->getAttributeLabel('seo_description') ],
            ],
        ];
    }

    public function getFieldsCategoryTemplates()
    {
        if ($this->fieldsCategory) {
            $arr = [];
            $bsModel = new BlockSection();
            foreach ($this->fieldsCategory as $field) {
                $arr[] = [
                    'type' => $field->type,
                    'value' => $field->value,
                    'name' => $field->name ?? "",
                    'hint' => $bsModel->getAttributeLabel($field->value),
                ];
            }
            return $arr;
        }
        return $this->getDefaultFieldsCategoryTemplates();
    }

    public function getDefaultFieldsCategoryTemplates()
    {
        $bsModel = new BlockSection();
        return [
            [ 'type' => 'model', 'name' => "", 'value' => 'photo_preview', 'hint' => $bsModel->getAttributeLabel('photo_preview') ],
            [ 'type' => 'model', 'name' => "", 'value' => 'title', 'hint' => $bsModel->getAttributeLabel('title')],
            [ 'type' => 'model', 'name' => "", 'value' => 'anons', 'hint' => $bsModel->getAttributeLabel('anons') ],
            [ 'type' => 'model', 'name' => "", 'value' => 'public', 'hint' => $bsModel->getAttributeLabel('public') ],
            [ 'type' => 'model', 'name' => "", 'value' => 'id', 'hint' => $bsModel->getAttributeLabel('id') ],
            [ 'type' => 'model', 'name' => "", 'value' => 'update_date', 'hint' => $bsModel->getAttributeLabel('update_date') ],
        ];
    }

    ############################

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blocks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['create_user', 'update_user', 'sort'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['title', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'alias' => 'Url',
            'sort' => 'Сортировка',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Отредактировал',
            'update_date' => 'Дата обн.',
        ];
    }

    public function getSectionList($divider = '.')
    {
        $categories = BlockSection::find()
            ->where(['block_id' => $this->id])
            ->orderBy('left');

        return ArrayHelper::map($categories->all(), 'id', static function(BlockSection $row) use($divider) {
            return str_repeat($divider, $row->depth) . ' ' . $row->title;
        });
    }

    public function getCategory()
    {
        return $this->hasOne(BlockSection::class, ['block_id' => 'id']);
    }

    public function getProperties()
    {
        return $this->hasMany(BlockProperty::class, ['block_id' => 'id']);
    }

    public function getTranslate()
    {
        return $this->hasOne(BlockTranslate::class, ['block_id' => 'id']);
    }

    public function getFields()
    {
        return $this->hasMany(BlockField::class, ['block_id' => 'id'])->onCondition(['block_type' => BlockField::TYPE_BLOCK_ITEM]);
    }

    public function getFieldsCategory()
    {
        return $this->hasMany(BlockField::class, ['block_id' => 'id'])->onCondition(['block_type' => BlockField::TYPE_BLOCK_CATEGORY]);
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SlugBehavior::class,
                'in_attribute' => 'title',
                'out_attribute' => 'alias'
            ],
            [
                'class' => AttributesBehavior::class,
                'attributes' => [
                    'create_user' => [
                        BaseActiveRecord::EVENT_BEFORE_INSERT => \Yii::$app->user->id,
                    ],
                    'create_date' => [
                        BaseActiveRecord::EVENT_BEFORE_INSERT => date('Y-m-d H:i:s'),
                    ],
                    'update_user' => [
                        BaseActiveRecord::EVENT_BEFORE_UPDATE => \Yii::$app->user->id,
                    ],
                    'update_date' => [
                        BaseActiveRecord::EVENT_BEFORE_UPDATE => date('Y-m-d H:i:s'),
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     * @return self
     */
    public static function findOrFail($id)
    {
        if (($model = self::find()->with('fields.children')->where(['id' => $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
