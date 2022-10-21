<?php

namespace thefx\blocks\models\files;

use Yii;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property int $id
 * @property string $file
 * @property string $title
 * @property int $size
 * @property int $sort
 * @property string $description
 */
class Files extends \yii\db\ActiveRecord
{
    public $savePath;
    public $webPath;

    public $deleteFromDisc;

    public function __construct(array $config = [])
    {
        $this->savePath = '@webroot/upload/blocks/';
        $this->webPath = '/upload/blocks/';
        $this->deleteFromDisc = true;
        parent::__construct($config);
    }

    /**
     * @param $filename
     * @return Files
     */
    public function create($filename)
    {
        $model = new self();
        $model->file = $filename;
        $model->title = $filename;
        return $model;
    }

    /**
     * Removes image from db and disk
     *
     * @param $filename
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function removeFile($filename)
    {
        if ($this->deleteFromDisc) {
            $fullPath = Yii::getAlias($this->savePath . $filename);
            if (is_file($fullPath)) {unlink($fullPath);}
        }
        if (($model = $this->findByFilename($filename)) !== null) {
            $model->delete();
        }
    }

    public function findByFilename($filename)
    {
        return self::findOne(['file' => $filename]);
    }

    public function getPath()
    {
        return  \Yii::getAlias($this->webPath) . '/' . $this->file;
    }

    public function __toString()
    {
        return $this->getPath();
    }

    public static function tableName()
    {
        return '{{%files}}';
    }

    public function rules()
    {
        return [
            [['file', 'title'], 'required'],
            [['size', 'sort'], 'integer'],
            [['file', 'title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['file'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'Имя',
            'title' => 'Название',
            'description' => 'Описание',
            'size' => 'Размер',
            'sort' => 'Сортировка',
        ];
    }
}
