<?php

namespace thefx\blocks\models;

use thefx\blocks\models\queries\BlockItemQuery;
use thefx\user\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%block_item}}".
 *
 * @property int $id
 * @property int|null $block_id
 * @property string|null $title
 * @property string|null $alias
 * @property string|null $anons
 * @property string|null $text
 * @property string|null $photo
 * @property string|null $photo_preview
 * @property string|null $date
 * @property int $section_id
 * @property int $public
 * @property int $sort
 * @property string|null $seo_title
 * @property string|null $seo_keywords
 * @property string|null $seo_description
 * @property int|null $create_user
 * @property string|null $create_date
 * @property int|null $update_user
 * @property string|null $update_date
 * @property BlockSections $category
 * @property BlockProperty[] $propertiesAll
 * @property BlockProperty[] $propertiesIndexed
 * @property BlockItemPropertyAssignments[] $propertyAssignments
 * @property BlockItemPropertyAssignments[] $propertyAssignmentsIndexed
 * @property BlockItemPropertyAssignments[] $propertyAssignmentsFilter
 * @property Block $block
 * @property User[] $createUser
 */
class BlockItem extends ActiveRecord
{
    public function getPhotoPreviewPath($prefix = '')
    {
        return (Yii::$app->params['blockItem' . $this->section_id]['photo_preview']['urlDir'] ?? Yii::$app->params['blockItem']['photo_preview']['urlDir']) . $prefix . $this->photo_preview;
    }

    public function getPhotoPath($prefix = '')
    {
        return (Yii::$app->params['blockItem' . $this->section_id]['photo']['urlDir'] ?? Yii::$app->params['blockItem']['photo']['urlDir']) . $prefix . $this->photo;
    }

    public function getSectionList()
    {
        /** @var BlockSections $category */
        $category = BlockSections::findOne(['id' => $this->section_id]);
        $categories = BlockSections::find()->where(['block_id' => $category->block_id])->orderBy('left')->all();

        return ArrayHelper::map($categories, 'id', static function($row) {
            return str_repeat('↳', $row->depth) . ' ' . $row->title;
        });
    }

    public function getPropertyValue($code = null)
    {
        $value = null;
        foreach ($this->propertyAssignments as $item) {
            if ($item->property->code === $code) {
                switch ($item->property->type) {
                    case BlockProperty::TYPE_LIST:
                        $value[$item->getValue()->id] = $item->getValue()->title;
                        break;
                    case BlockProperty::TYPE_FILE:
                        $value[] = $item->getValue();
                        break;
                    default:
                        $value[] = $item->getValue();
                }
                if (!$item->property->isMultiple()) {
                    return reset($value);
                }
            }
        }
        return $value;
    }

    /**
     * @param $categoryAlias
     * @param $slug
     * @param $sectionAlias
     * @return array|BlockItem
     * @throws NotFoundHttpException
     */
    public static function getBySectionAndSlug($categoryId, $slug, $sectionAlias = null)
    {
        $model = self::find()
            ->alias('c')
            ->with([
                'propertyAssignments.file',
                'propertyAssignments.property',
                'propertyAssignments.propertyElement',
            ])
            ->joinWith(['section section', 'category category'])
            ->andWhere(['c.alias' => $slug])
            ->andWhere(['category.id' => $categoryId])
            ->andWhere(['c.status' => 1])
            ->andFilterWhere(['section.alias' => $sectionAlias])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException('Объект не найден');
        }
        return $model;
    }

    public function getMetaDescription()
    {
        return $this->seo_description ?: mb_substr(strip_tags($this->title), 0, 255);
    }

    public function getMetaKeywords()
    {
        return $this->seo_keywords;
    }

    #########################

    public function getBlock()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    public function getPropertiesAll()
    {
        return $this->hasMany(BlockProperty::class, ['block_id' => 'block_id']);
    }

    public function getPropertyAssignments()
    {
        return $this->hasMany(BlockItemPropertyAssignments::class, ['block_item_id' => 'id']);
    }

//    public function getPropertiesIndexed()
//    {
//        return $this->hasMany(BlockProperty::class, ['block_id' => 'block_id'])->indexBy('code');
//    }

//    public function getPropertyAssignmentsIndexed()
//    {
//        return $this->hasMany(BlockItemPropertyAssignments::class, ['block_item_id' => 'id'])->indexBy('id');
//    }

//    public function getPropertyAssignmentsFilter()
//    {
//        return $this->hasMany(BlockItemPropertyAssignments::class, ['block_item_id' => 'id']);
//    }

//    public function getSection()
//    {
//        return $this->hasOne(BlockSections::class, ['id' => 'section_id']);
//    }

    public function getCreateUser()
    {
        return $this->hasOne(User::class, ['id' => 'create_user']);
    }

    #########################

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
            [['title', 'block_id', 'section_id', 'sort'], 'required'],
            [['anons', 'text'], 'string'],
            [['date', 'create_date', 'update_date'], 'safe'],
            [['block_id', 'section_id', 'public', 'sort', 'create_user', 'update_user'], 'integer'],
            [['title', 'alias', /*'photo', 'photo_preview',*/ 'photo_crop', 'photo_preview_crop', 'seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],
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
            'alias' => 'Url',
            'anons' => 'Краткое описание',
            'text' => 'Текст',
            'photo' => 'Фото',
            'photo_preview' => 'Фото для анонса',
            'date' => 'Дата',
            'section_id' => 'Категория',
            'public' => 'Активность',
            'sort' => 'Сортировка',
            'seo_title' => 'Заголовок в браузере',
            'seo_keywords' => 'Ключевые слова',
            'seo_description' => 'Описание',
            'create_user' => 'Создал',
            'create_date' => 'Дата создания',
            'update_user' => 'Редактировал',
            'update_date' => 'Дата обн.',
        ];
    }

//    /**
//     * @param $blockId
//     * @param $slug
//     * @return BlockItem|array
//     */
//    public static function getBySlug($blockId, $alias)
//    {
//        return self::find()
//            ->with(['propertyAssignments.prop'])
//            ->where(['alias' => $alias])
//            ->andWhere(['block_id' => $blockId])
//            ->active()
//            ->one();
//    }

    /**
     * @inheritdoc
     * @return BlockItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockItemQuery(static::class);
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
