<?php

namespace thefx\blocks\models;

/**
 * This is the model class for table "{{%block_files}}".
 *
 * @property int $id
 * @property int|null $height
 * @property int|null $width
 * @property int|null $size
 * @property string|null $path
 * @property string|null $file_name
 * @property string|null $description
 * @property string|null $create_date
 * @property int|null $create_user
 */
class BlockFiles extends \yii\db\ActiveRecord
{
    public static function create($path, $url, $filename)
    {
        $fullDir = \Yii::getAlias($path);
        [$width, $height] = getimagesize($fullDir . $filename);

        $model = new self();
        $model->path = $url;
        $model->file_name = $filename;
        $model->size = filesize($fullDir . $filename);
        $model->width = $width;
        $model->height = $height;

        $model->create_user  = \Yii::$app->user->id;
        $model->create_date  = date('Y-m-d H:i:s');

        return $model;
    }

    public function getFileWithPath()
    {
        return $this->path . $this->file_name;
    }

    public function __toString()
    {
        return $this->getFileWithPath();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%block_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['height', 'width', 'size', 'create_user'], 'integer'],
            [['create_date'], 'safe'],
            [['file_name', 'path', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'height' => 'Height',
            'width' => 'Width',
            'size' => 'Size',
            'path' => 'Path',
            'file_name' => 'File Name',
            'description' => 'Description',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
        ];
    }
}
