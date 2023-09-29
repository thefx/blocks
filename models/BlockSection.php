<?php

namespace thefx\blocks\models;

use paulzi\nestedsets\NestedSetsQueryTrait;
use thefx\blocks\behaviors\SlugBehavior;
use thefx\blocks\behaviors\UploadImageBehavior;
use thefx\blocks\components\NestedSetsBehavior;
use thefx\blocks\components\TreeHelperNested;
use thefx\blocks\models\queries\BlockSectionsQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%block_sections}}".
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
 * @property int $section_id
 * @property int $left
 * @property int $right
 * @property int $depth
 * @property int $create_user
 * @property string $create_date
 * @property int $update_user
 * @property string $update_date
 * @property int $public
 * @property BlockField[] $fields
 * @property Block $block
 * @property BlockItem[] $items
 * @property int $sort [int(11)]
 * @property string $alias [varchar(255)]
 * @property string $icon
 *
 * @mixin NestedSetsBehavior
 * @mixin NestedSetsQueryTrait
 */
class BlockSection extends ActiveRecord
{
    public $type;
    public $photo_preview_crop;
    public $photo_crop;

    const TYPE_FOLDER = 'folder';
    const TYPE_ITEM = 'item';

    public static function create($block_id, $section_id)
    {
        $model = new self();
        $model->block_id = $block_id;
        $model->section_id = $section_id;
        $model->sort = 100;
        $model->public = 1;
        $model->create_user = Yii::$app->user->id;
        $model->create_date = date('Y-m-d H:i:s');

        return $model;
    }

    public static function getLastNode($block_id, $exclude_id)
    {
        return self::find()
            ->where(['block_id' => $block_id, 'section_id' => 0])
            ->andFilterWhere(['!=', 'id', $exclude_id])
            ->orderBy('right DESC')
            ->one();
    }

    public function isFolder()
    {
        return $this->type == self::TYPE_FOLDER;
    }

    public static function getSectionsList($block_id, $except_section_id = null)
    {
        $categories = static::find()
            ->where(['block_id' => $block_id])
            ->orderBy('left')
            ->all();

        $tree = TreeHelperNested::createTreeList($categories, $except_section_id);
        return ArrayHelper::map($tree, 'id', 'title');
    }

    public function getItems()
    {
        return $this->hasMany(BlockItem::class, ['section_id' => 'id']);
    }

    public function getBlock()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    public function getPhotoPreviewPath($prefix = '')
    {
        return (Yii::$app->params['blockSection' . $this->section_id]['photo_preview']['urlDir'] ?? Yii::$app->params['blockSection']['photo_preview']['urlDir']) . $prefix . $this->photo_preview;
    }

    public function getPhotoPath($prefix = '')
    {
        return (Yii::$app->params['blockSection' . $this->section_id]['photo']['urlDir'] ?? Yii::$app->params['blockSection']['photo']['urlDir']) . $prefix . $this->photo;
    }

    public function beforeValidate()
    {
        $settings = array_merge(Yii::$app->params['blockSection'], Yii::$app->params['blockSection' . $this->block_id] ?? []);

        $this->attachBehaviors([
            'photo_preview' => [
                'class' => UploadImageBehavior::class,
                'attributeName' => 'photo_preview',
                'cropCoordinatesAttrName' => 'photo_preview_crop',
                'savePath' => $settings['photo_preview']['dir'],
                'dir' => $settings['photo_preview']['urlDir'],
                'defaultCrop' => $settings['photo_preview']['defaultCrop'],
                'crop' => $settings['photo_preview']['crop'],
                'generateNewName' => true,
            ],
            'photo' => [
                'class' => UploadImageBehavior::class,
                'attributeName' => 'photo',
                'cropCoordinatesAttrName' => 'photo_crop',
                'savePath' => $settings['photo']['dir'],
                'dir' => $settings['photo_preview']['urlDir'],
                'defaultCrop' => $settings['photo']['defaultCrop'],
                'crop' => $settings['photo']['crop'],
                'generateNewName' => true,
            ]
        ]);

        return parent::beforeValidate();
    }

    #########################

    public function behaviors() {
        return [
            'slug' => [
                'class' => SlugBehavior::class,
                'in_attribute' => 'title',
                'out_attribute' => 'alias'
            ],
            [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'block_id',
                'leftAttribute' => 'left',
                'rightAttribute' => 'right',
                'depthAttribute' => 'depth',
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
     */
    public static function tableName()
    {
        return '{{%block_sections}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['block_id', 'section_id', 'left', 'right', 'depth', 'create_user', 'update_user', 'public', 'sort'], 'integer'],
            [['anons', 'text', 'icon'], 'string'],
            [['date', 'create_date', 'update_date'], 'safe'],
//            [['lft', 'rgt', 'depth'], 'required'],
            [['title', 'alias', 'photo_crop', 'photo_preview_crop', 'seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],
            [['photo', 'photo_preview'], 'file', 'mimeTypes' => 'image/*'],
            [['section_id'], 'default', 'value' => 0],
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
            'alias' => 'Url',
            'anons' => 'Краткое описание',
            'text' => 'Подробное описание',
            'photo' => 'Фото',
            'photo_preview' => 'Фото для анонса',
            'icon' => 'Иконка',
            'date' => 'Дата создания',
            'section_id' => 'Категория',
            'left' => 'left',
            'right' => 'right',
            'depth' => 'Depth',
            'seo_title' => 'Заголовок в браузере',
            'seo_keywords' => 'Ключевые слова',
            'seo_description' => 'Описание',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Редактировал',
            'update_date' => 'Дата обн.',
            'public' => 'Активность',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @inheritdoc
     * @return BlockSectionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockSectionsQuery(static::class);
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
