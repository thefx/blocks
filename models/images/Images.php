<?php

namespace thefx\blocks\models\images;

use app\shop\entities\Image\queries\ImageQuery;
use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property int $id
 * @property string $file
 * @property string $title
 * @property int $width
 * @property int $height
 * @property int $size
 * @property int $sort
 */
class Images extends \yii\db\ActiveRecord
{
    public $savePath;

    public $deleteFromDisc;

    public $crop;

    public function __construct(array $config = [])
    {
        $this->savePath = '@webroot/upload/blocks/';
        $this->deleteFromDisc = true;
        $this->crop = ['prev'];
        parent::__construct($config);
    }

    /**
     * @param $filename
     * @param $width
     * @param $height
     */
    public function addImage($filename, $width, $height)
    {
        $model = new self();
        $model->file = $filename;
        $model->title = $filename;
        $model->width = $width;
        $model->height = $height;
        $model->save() or die(var_dump($model->errors));
    }

    /**
     * Removes image from db and disk
     *
     * @param $filename
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeImage($filename)
    {
        if ($this->deleteFromDisc) {
            $fullPath = Yii::getAlias($this->savePath . $filename);
            if (is_file($fullPath)) unlink($fullPath);

            foreach ($this->crop as $pre) {
                $fullPathCrop = Yii::getAlias($this->savePath . $pre . '_' .  $filename);
                if (is_file($fullPathCrop)) {
                    unlink($fullPathCrop);
                }
            }
        }
        if ($model = $this->findByFilename($filename)) {
            $model->delete();
        }
    }

    public function findByFilename($filename)
    {
        return self::findOne(['file' => $filename]);
    }

    public static function tableName()
    {
        return '{{%image}}';
    }

    public function rules()
    {
        return [
            [['file', 'title'], 'required'],
            [['width', 'height', 'size', 'sort'], 'integer'],
            [['file', 'title'], 'string', 'max' => 255],
            [['file'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'title' => 'Title',
            'width' => 'Width',
            'height' => 'Height',
            'size' => 'Size',
            'sort' => 'Sort',
        ];
    }
}
