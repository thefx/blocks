<?php

namespace thefx\blocks\models\blocks;

use thefx\blocks\models\blocks\queries\BlockSettingsQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%block_settings}}".
 *
 * @property int $id
 * @property int $block_id
 * @property string $upload_path
 * @property int $photo_crop_width
 * @property int $photo_crop_height
 * @property int $photo_crop_type
 * @property int $photo_preview_crop_width
 * @property int $photo_preview_crop_height
 * @property int $photo_preview_crop_type
 * @property Block $block
 */
class BlockSettings extends ActiveRecord
{
    const CROP_TYPE_WIDEN = 'widen';
    const CROP_TYPE_FIT = 'fit';

    public static function create()
    {
        $model = new self();
        $model->upload_path = 'blocks';
        $model->photo_crop_width = 900;
        $model->photo_crop_type = self::CROP_TYPE_WIDEN;
        $model->photo_preview_crop_width = 316;
        $model->photo_preview_crop_height = 243;
        $model->photo_preview_crop_type = self::CROP_TYPE_WIDEN;
        return $model;
    }

    public function getBlock()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    public function listCropTypes()
    {
        return [
            self::CROP_TYPE_WIDEN => 'widen',
            self::CROP_TYPE_FIT => 'fit',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['block_id'], 'required'],
            [['block_id', 'photo_crop_width', 'photo_crop_height', 'photo_preview_crop_width', 'photo_preview_crop_height'], 'integer'],
            [['photo_crop_type', 'photo_preview_crop_type'], 'string', 'max' => 10],
            [['upload_path'], 'string', 'max' => 255],
            [['block_id'], 'unique'],
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
            'upload_path' => 'Путь для загрузки',
            'photo_crop_width' => 'Ширина фото, px',
            'photo_crop_height' => 'Высота фото, px',
            'photo_crop_type' => 'Тип кропа',
            'photo_preview_crop_width' => 'Ширина фото для превью, px',
            'photo_preview_crop_height' => 'Высота фото для превью, px',
            'photo_preview_crop_type' => 'Тип кропа для превью',
        ];
    }

    /**
     * @inheritdoc
     * @return BlockSettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockSettingsQuery(static::class);
    }
}
