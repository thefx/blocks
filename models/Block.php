<?php

namespace thefx\blocks\models;

use thefx\blocks\behaviors\Slug;
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
 * @property BlockSections $category
 * @property BlockProperty[] $properties
 * @property BlockTranslate $translate
 * @property BlockFields[] $fields
 * @property array $fieldsTemplates
 * @property array $defaultFieldsCategoryTemplates
 * @property array $fieldsCategoryTemplates
 * @property array $defaultFieldsTemplates
 * @property BlockFields[] $fieldsCategory
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
        $model->create_user  = \Yii::$app->user->id;
        $model->create_date  = date('Y-m-d H:i:s');

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
            foreach ($this->fields as $field) {
                if ($field->parent_id == 0) {
                    $children = [];
                    foreach ($field->children as $item) {
                        $hint = $withHint && $item->type === BlockFields::TYPE_PROP && $item->property ? (($item->property->isRequired() ? '~' : '') . $item->property->title) : null;
                        $children[] = [
                            'type' => $item->type,
                            'value' => $item->value,
                            'hint' => $hint,
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

        /** @var BlockProperty $prop */
        foreach ($this->props as $prop) {
            $propsRows[] = ['type' => 'prop', 'value' => $prop->id, 'hint' => ($prop->isRequired() ? '~' : '') . $prop->title];
        }

        return [
            'Краткая информация' => [
                [ 'type' => 'model', 'value' => 'title' ],
                [ 'type' => 'model', 'value' => 'alias' ],
                [ 'type' => 'model', 'value' => 'date' ],
                [ 'type' => 'model', 'value' => 'anons' ],
                [ 'type' => 'model', 'value' => 'photo_preview' ],
                [ 'type' => 'model', 'value' => 'section_id' ],
                [ 'type' => 'model', 'value' => 'public' ],
                [ 'type' => 'model', 'value' => 'sort' ],
            ],
            'Подробная информация' => [
                [ 'type' => 'model', 'value' => 'photo' ],
                [ 'type' => 'model', 'value' => 'text' ],
            ],
            'Характеристики' => $propsRows,
            'Сео' => [
                [ 'type' => 'model', 'value' => 'seo_title' ],
                [ 'type' => 'model', 'value' => 'seo_keywords' ],
                [ 'type' => 'model', 'value' => 'seo_description' ],
            ],
        ];
    }

    public function getFieldsCategoryTemplates()
    {
        if ($this->fieldsCategory) {
            $arr = [];
            foreach ($this->fieldsCategory as $field) {
                $arr[] = [
                    'type' => $field->type,
                    'value' => $field->value,
                    'name' => $field->name,
                ];
            }
            return $arr;
        }
        return $this->getDefaultFieldsCategoryTemplates();
    }

    public function getDefaultFieldsCategoryTemplates()
    {
        return [
            [ 'type' => 'model', 'value' => 'title' ],
            [ 'type' => 'model', 'value' => 'anons' ],
            [ 'type' => 'model', 'value' => 'public' ],
            [ 'type' => 'model', 'value' => 'id' ],
            [ 'type' => 'model', 'value' => 'update_date' ],
        ];
    }

    ############################

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block}}';
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
        $categories = BlockSections::find()
            ->where(['block_id' => $this->id])
            ->orderBy('left');

        return ArrayHelper::map($categories->all(), 'id', static function(BlockSections $row) use($divider) {
            return str_repeat($divider, $row->depth) . '' . $row->title;
        });
    }

    public function getCategory()
    {
        return $this->hasOne(BlockSections::class, ['block_id' => 'id']);
    }

    public function getProps()
    {
        return $this->hasMany(BlockProperty::class, ['block_id' => 'id']);
    }

    public function getTranslate()
    {
        return $this->hasOne(BlockTranslate::class, ['block_id' => 'id']);
    }

    public function getFields()
    {
        return $this->hasMany(BlockFields::class, ['block_id' => 'id'])->onCondition(['block_type' => BlockFields::TYPE_BLOCK_ITEM]);
    }

    public function getFieldsCategory()
    {
        return $this->hasMany(BlockFields::class, ['block_id' => 'id'])->onCondition(['block_type' => BlockFields::TYPE_BLOCK_CATEGORY]);
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => Slug::class,
                'in_attribute' => 'title',
                'out_attribute' => 'alias'
            ],
            [
                'class' => AttributesBehavior::class,
                'attributes' => [
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

//    public function transactions()
//    {
//        return [
//            self::SCENARIO_DEFAULT => self::OP_ALL,
//        ];
//    }

//    /**
//     * @inheritdoc
//     * @return BlockQuery the active query used by this AR class.
//     */
//    public static function find()
//    {
//        return new BlockQuery(static::class);
//    }

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
