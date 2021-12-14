<?php

namespace thefx\blocks\models\blocks;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use thefx\blocks\behaviors\Slug;
use thefx\blocks\models\blocks\queries\BlockQuery;
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
 * @property string $path
 * @property string $table
 * @property string $template
 * @property int $pagination
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
 * @property User $createUser
 * @property User $updateUser
 * @property int $sort [int(10)]
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

        $model->create_user  = \Yii::$app->user->id;
        $model->create_date  = date('Y-m-d H:i:s');

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
            'Каталог' => [
                [ 'type' => 'model', 'value' => 'article' ],
                [ 'type' => 'model', 'value' => 'price' ],
                [ 'type' => 'model', 'value' => 'price_old' ],
                [ 'type' => 'model', 'value' => 'currency' ],
                [ 'type' => 'model', 'value' => 'unit' ],
            ],
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
            [['create_user', 'update_user', 'sort', 'pagination'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['title', 'path', 'table', 'template'], 'string', 'max' => 255],
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
            'sort' => 'Сортировка',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Отредактировал',
            'update_date' => 'Дата обн.',
        ];
    }

    public function categoryList($divider = '.')
    {
        $categories = BlockCategory::find()
            ->where(['block_id' => $this->id])
            ->orderBy('lft');

        return ArrayHelper::map($categories->all(), 'id', static function(BlockCategory $row) use($divider) {
            return str_repeat($divider, $row->depth) . '' . $row->title;
        });
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
            Slug::class,
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
