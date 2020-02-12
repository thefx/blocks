<?php

namespace thefx\blocks\models\blocks;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use thefx\blocks\models\blocks\queries\BlockQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block}}".
 *
 * @property int $id
 * @property string $title
 * @property string $path
 * @property string $table
 * @property string $template
 * @property string $pagination
 * @property int $create_user
 * @property string $create_date
 * @property int $update_user
 * @property string $update_date
 * @property BlockCategory $category
 * @property BlockProp[] $props
 * @property BlockSettings $settings
 * @property BlockTranslate $translate
 * @property BlockFields[] $fields
 * @property array $fieldsTemplates
 * @property array $defaultFieldsCategoryTemplates
 * @property array $fieldsCategoryTemplates
 * @property array $defaultFieldsTemplates
 * @property BlockFields[] $fieldsCategory
 */
class Block extends ActiveRecord
{
    public static function create($title, $path)
    {
        $model = new self();
        $model->title = $title;
        $model->path  = $path;

        $model->settings  = BlockSettings::create();
        $model->translate = BlockTranslate::create();
        $model->category  = BlockCategory::createRoot();

        return $model;
    }

    /**
     * @param $code
     * @return BlockProp|null
     */
    public function getPropByCode($code)
    {
        foreach ($this->props as $prop) {
            if ($prop->code === $code) {
                return $prop;
            }
        }
        return null;
    }

    public function getFieldsTemplates()
    {
        if ($this->fields) {
            $arr = [];
            foreach ($this->fields as $field) {
                if ($field->parent_id == 0) {
                    $children = [];
                    foreach ($field->children as $item) {
                        $children[] = [ 'type' => $item->type, 'value' => $item->value ];
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

        foreach ($this->props as $prop) {
            $propsRows[] = [ 'type' => 'prop', 'value' => $prop->id ];
        }

        return [
            'Краткая информация' => [
                [ 'type' => 'model', 'value' => 'date' ],
                [ 'type' => 'model', 'value' => 'title' ],
                [ 'type' => 'model', 'value' => 'path' ],
                [ 'type' => 'model', 'value' => 'anons' ],
                [ 'type' => 'model', 'value' => 'photo_preview' ],
                [ 'type' => 'model', 'value' => 'parent_id' ],
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
                    'value' => $field->value
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
            [['create_user', 'update_user'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['title', 'path', 'table', 'template', 'pagination'], 'string', 'max' => 255],
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
            'path' => 'Путь',
            'table' => 'Таблица',
            'template' => 'Шаблон',
            'pagination' => 'Элементов на страницу',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Отредактировал',
            'update_date' => 'Дата редактирования',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(BlockCategory::class, ['block_id' => 'id']);
    }

    public function getProps()
    {
        return $this->hasMany(BlockProp::class, ['block_id' => 'id']);
    }

    public function getSettings()
    {
        return $this->hasOne(BlockSettings::class, ['block_id' => 'id']);
    }

    public function getTranslate()
    {
        return $this->hasOne(BlockTranslate::class, ['block_id' => 'id']);
    }

    public function getFields()
    {
        return $this->hasMany(BlockFields::class, ['block_id' => 'id'])->onCondition(['block_type' => BlockFields::BLOCK_TYPE_ITEM]);
    }

    public function getFieldsCategory()
    {
        return $this->hasMany(BlockFields::class, ['block_id' => 'id'])->onCondition(['block_type' => BlockFields::BLOCK_TYPE_CATEGORY]);
    }

    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'category', /*=> ['cascadeDelete' => true]*/
                    'props',
                    'settings',
                    'translate',
                    'fields',
                    'fieldsCategory',
                ],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     * @return BlockQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockQuery(static::class);
    }
}
