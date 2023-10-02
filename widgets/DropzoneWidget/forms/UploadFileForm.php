<?php

namespace thefx\blocks\widgets\DropzoneWidget\forms;

use thefx\blocks\models\BlockFile;
use Intervention\Image\ImageManager;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * https://www.yiiframework.com/doc/guide/2.0/ru/input-file-upload
 */
class UploadFileForm extends Model
{
    public $crop = []; // for images

    public $extensions = '';   // 'jpg, jpeg'

    public $file;

    public $path = '@frontend/web/upload/';

    public $url = '/upload/';

    public $fileAttribute = 'file';

    public $resizeQuality = 90;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['crop', 'file'], 'required'],
//            [['crop'], 'integer'], todo add rules ?
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => $this->extensions],
        ];
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, $this->fileAttribute);
        return parent::beforeValidate();
    }

    public function uploadFile()
    {
        if (!$this->validate()) {
            throw new \DomainException(implode(',', ArrayHelper::getColumn ($this->errors, 0, false)));
        }

        $ext = strtolower(pathinfo($this->file->name, PATHINFO_EXTENSION));
        $filename = uniqid('', false) . '.' . $ext;
        $fullDir = \Yii::getAlias($this->path);
        $this->createDirectory($fullDir);

        if (in_array($ext, ['jpg','jpeg','png'])) {
            $manager = new ImageManager(array('driver' => 'gd'));
            $img = $manager->make($this->file->tempName);
            foreach ($this->crop as $cropParam) {
                $img->resize($cropParam[0] > 0 ? $cropParam[0] : null, $cropParam[1] > 0 ? $cropParam[1] : null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($fullDir . $cropParam[2] . $filename, $this->resizeQuality);
            }
        } else {
            $this->file->saveAs($fullDir . $filename);
        }

        $model = BlockFile::create($this->path, $this->url, $filename);
        $model->save() or die(var_dump($model->errors));

        return [
            'photo_path' => $this->url . $model->file_name,
            'photo_path_preview' => $this->url . 'prev_' . $model->file_name,
            'update_time' => time(),
            'file' => $model->file_name,
            'name' => $model->file_name,
            'id' => $model->id,
        ];
    }

    private function createDirectory($path)
    {
        if (!is_dir($path) && !mkdir($dir = $path, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
}
