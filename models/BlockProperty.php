<?php
namespace thefx\blocks\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%block_property}}".
 *
 * @property int $id
 * @property int $block_id [int(11)]
 * @property string $title
 * @property string $type
 * @property int $public
 * @property int $multiple
 * @property int $required
 * @property int $sort
 * @property string $code
 * @property string $hint
 * @property Block $block
 * @property BlockPropertyElement[] $elements
 * @property BlockItemPropertyAssignment[] $assignments
 * @property BlockItemPropertyAssignment[] $blockItemsList
 * @property int $relative_item
 * @property int $relative_category
 * @property int $redactor
 * @property string $default_value [varchar(255)]
 * @property string $file_type [varchar(255)]
 * @property string $with_description
 */
class BlockProperty extends ActiveRecord
{
    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_LIST = 'list';
    public const TYPE_FILE = 'file';
    public const TYPE_RELATIVE_ITEM = 'relative_item';
    public const TYPE_CHECKBOX = 'checkbox';

    public static function create($block_id)
    {
        $model = new self();
        $model->sort  = 100;
        $model->block_id  = $block_id;
        $model->required  = 0;
        $model->multiple  = 0;
        $model->public  = 1;
        $model->redactor  = 0;

        return $model;
    }

    public function getTypes()
    {
        return [
            self::TYPE_STRING => 'Строка',
            self::TYPE_INT => 'Число',
            self::TYPE_TEXT => 'Текст/Html',
            self::TYPE_LIST => 'Список',
            self::TYPE_FILE => 'Файл',
            self::TYPE_CHECKBOX => 'Чекбокс',
            self::TYPE_RELATIVE_ITEM => 'Связанный блок (элемент)',
        ];
    }

    public function getTypeName(): string
    {
        return $this->getTypes()[$this->type];
    }

//    public function getCategoriesList(): array
//    {
//        $blocks = ContentCategories::find()->select(['id', 'title'])->all();
//
//        return ArrayHelper::map($blocks, 'id', 'title');
//    }

    public function getBlocksList(): array
    {
        $blocks = Block::find()->select(['id', 'title'])->all();

        return ArrayHelper::map($blocks, 'id', 'title');
    }

    public function isRequired(): bool
    {
        return $this->required === 1;
    }

    public function isMultiple(): bool
    {
        return $this->multiple === 1;
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INT;
    }

    public function isFile(): bool
    {
        return $this->type === self::TYPE_FILE;
    }

    public function isList(): bool
    {
        return $this->type === self::TYPE_LIST;
    }

    public function isRelativeItem(): bool
    {
        return $this->type === self::TYPE_RELATIVE_ITEM;
    }

    public function getBlock(): ActiveQuery
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    public function getElements(): ActiveQuery
    {
        return $this->hasMany(BlockPropertyElement::class, ['property_id' => 'id'])->orderBy('sort ASC, id DESC');
    }

    public function getAssignments(): ActiveQuery
    {
        return $this->hasMany(BlockItemPropertyAssignment::class, ['property_id' => 'id']);
    }

    public function getBlockItemsList(): ActiveQuery
    {
        return $this->hasMany(BlockItem::class, ['block_id' => 'relative_item']);
    }

    public function getAssignBlockItemsList(): array
    {
        return ArrayHelper::map($this->blockItemsList, 'id', static function ( $row) { return '[' . $row->id . '] ' . $row->title; });
    }

    ####################

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%block_properties}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'code', 'sort'], 'required'],
            [['block_id', 'public', 'multiple', 'required', 'with_description', 'sort', 'relative_item', 'relative_category', 'redactor'], 'integer'],
            [['title', 'type', 'code', 'hint', 'default_value'], 'string', 'max' => 255],
            [['redactor'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'block_id' => 'Категория',
            'title' => 'Название',
            'type' => 'Тип',
            'public' => 'Активно',
            'multiple' => 'Множественное',
            'required' => 'Обязательно',
            'sort' => 'Сортировка',
            'code' => 'Код',
            'hint' => 'Подсказка',
            'relative_item' => 'Связанный блок (элемент)',
            'relative_category' => 'Связанный блок (группа)',
            'redactor' => 'Редактор',
            'default_value' => 'Значение по умолчанию (Для чекбокса)',
            'with_description' => 'С описанием',
        ];
    }
}
