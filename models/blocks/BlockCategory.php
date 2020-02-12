<?php

namespace thefx\blocks\models\blocks;

use app\behaviors\Slug;
use paulzi\nestedsets\NestedSetsBehavior;
use paulzi\nestedsets\NestedSetsQueryTrait;
use thefx\blocks\models\blocks\queries\BlockCategoryQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%block_category}}".
 *
 * @property int $id
 * @property int $block_id
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
 * @property int $parent_id
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $create_user
 * @property string $create_date
 * @property int $update_user
 * @property string $update_date
 * @property int $public
 * @property BlockFields[] $fields
 * @property Block $block
 *
 * @mixin NestedSetsBehavior
 * @mixin NestedSetsQueryTrait
 *
 * @method isRoot()
 * @method isLeaf()
 */
class BlockCategory extends ActiveRecord
{
    public $type;

    const TYPE_FOLDER = 'folder';
    const TYPE_ITEM = 'item';

    public static function createRoot()
    {
        $model = new self();
        $model->title = 'Родительская категория';
        $model->path = '0';
        $model->parent_id = '0';
        $model->create_user = Yii::$app->user->id;
        $model->create_date = date('Y-m-d H:i:s');
        $model->makeRoot();
        return $model;
    }

    public function isNotRoot()
    {
        return $this->parent_id != 0;
    }

    public function isFolder()
    {
        return $this->type == self::TYPE_FOLDER;
    }

    public function categoryList()
    {
        $categories = static::find()->where(['block_id' => $this->block_id])->orderBy('lft')->all();

        $arr =  ArrayHelper::map($categories, 'id', function($row) {
            return str_repeat('—', $row->depth) . ' ' . $row->title;
        });

        return $arr;
    }

    public function getBlock()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    #########################

    public function behaviors() {
        return [
            'slug' => [
                'class' => Slug::class,
                'in_attribute' => 'title',
                'out_attribute' => 'path'
            ],
            [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'block_id',
            ],
//            [
//                'class' => SaveRelationsBehavior::class,
//                'relations' => ['fields'],
//            ],
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
     */
    public static function tableName()
    {
        return '{{%block_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['block_id', 'parent_id', 'lft', 'rgt', 'depth', 'create_user', 'update_user', 'public'], 'integer'],
            [['anons', 'text'], 'string'],
            [['date', 'create_date', 'update_date'], 'safe'],
//            [['lft', 'rgt', 'depth'], 'required'],
            [['title', 'path', 'photo', 'photo_preview', 'seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_id' => 'Block ID',
            'title' => 'Название',
            'path' => 'Url',
            'anons' => 'Краткое описание',
            'text' => 'Подробное описание',
            'photo' => 'Фото',
            'photo_preview' => 'Фото для анонса',
            'date' => 'Дата создания',
            'parent_id' => 'Parent ID',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'seo_title' => 'Заголовок в браузере',
            'seo_keywords' => 'Ключевые слова',
            'seo_description' => 'Описание',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Редактировал',
            'update_date' => 'Дата редактирования',
            'public' => 'Активность',
        ];
    }

    /**
     * @inheritdoc
     * @return BlockCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockCategoryQuery(static::class);
    }
}
