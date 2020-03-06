<?php
/**
 * @link https://github.com/himiklab/yii2-upload-file-behavior
 * @copyright Copyright (c) 2014 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace thefx\blocks\behaviors;

use thefx\blocks\models\images\Images;
use Yii;
use yii\base\Behavior;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use Intervention\Image\ImageManager;

/**
 * Behavior for simplifies file upload
 *
 * For example:
 *
 * ```php
 * public function behaviors()
 * {
 *      return [
 *          'file' => [
 *              'class' => UploadFileBehavior::className(),
 *              'attributeName' => 'picture',
 *              'savePath' => '@webroot/uploads',
 *              'generateNewName' => true,
 *              'protectOldValue' => true,
 *              'defaultCrop' => [width,height,type_crop (fit or widen)],
 *              'crop'=>[
 *                   [width,height,prefix,type_crop (fit or widen)],
 *                   [300,150,'min','fit'],
 *                   [600,300,'max','widen']
 *               ]
 *          ],
 *      ];
 * }
 * ```
 *
 * @author HimikLab
 * @author thefx
 * @property ActiveRecord $owner
 */
class UploadImageBehavior extends Behavior
{
    /** @var string model file field name */
    public $attributeName = '';

    public $cropCoordinatesAttrName = '';
    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $savePath = '';
    /**
     * @var bool|callable generate a new unique name for the file
     * set true (@see self::generateFileName()) or anonymous function takes the old file name and returns a new name
     */
    public $generateNewName = false;

    /**
     * Базовый кроп
     * [850,480]
     * @var array
     */
    public $defaultCrop = [];

    /**
     * Дополнительный кроп
     * [
     *      [850,0,'nw','widen'],
     *      [850,480,'in','fit'],
     * ]
     * @var array
     */
    public $crop = [];

    /**
     * @var bool erase protection the old value of the model attribute if the form returns empty string
     */
    public $protectOldValue = true;

    public $deleteOldImages = false;

    /**
     * @var string|null
     */
    public $watermark = null;

    /**
     * @var UploadedFile[]
     */
    protected $files;

    protected $imageManager;

    public function __construct(Images $imageManager, array $config = [])
    {
        $this->imageManager = $imageManager;
        parent::__construct($config);
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function init()
    {
        if ($this->savePath instanceof \Closure) {
            $this->savePath = call_user_func($this->savePath);
        }
        $this->savePath = Yii::getAlias($this->savePath);
    }

    public function beforeValidate()
    {
        $model = $this->owner;

        if (is_array($model->getAttribute($this->attributeName))) {
            if (current($model->getAttribute($this->attributeName)) instanceof yii\web\UploadedFile) {
                $this->files = $model->getAttribute($this->attributeName);
                return true;
            }
            $this->files = UploadedFile::getInstances($model, $this->attributeName);
            if (!empty($this->files)) {
                $model->setAttribute($this->attributeName, $this->files);
                return true;
            }
            $model->setAttribute($this->attributeName, null);
            return true;
        }

        if (!$model->getAttribute($this->attributeName) instanceof yii\web\UploadedFile && $file = UploadedFile::getInstance($model, $this->attributeName)) {
            $this->files = [$file];
            $model->setAttribute($this->attributeName, $file);
            return true;
        }

        $this->files = [$model->getAttribute($this->attributeName)];
        return true;

        // TODO
//        $this->savePath = Yii::getAlias(Yii::$app->params['parts'][$this->owner->check->role]['dir']);
//        $this->defaultCrop = Yii::$app->params['parts'][$this->owner->check->role]['defaultCrop'];
//        $this->crop = Yii::$app->params['parts'][$this->owner->check->role]['crop'];
    }

    public function beforeInsert()
    {
        $this->loadFiles();
    }

    public function beforeUpdate()
    {
        $model = $this->owner;
        $this->loadFiles();

        if ($this->protectOldValue) {
            $model->setAttribute(
                $this->attributeName,
                $model->getOldAttribute($this->attributeName)
            );
            return;
        }
    }

    public function beforeDelete()
    {
        $this->deleteFiles();
    }

    protected function loadFiles()
    {
        $model = $this->owner;

        if (is_array($this->files) && !empty($this->files)) {
            $filenames = [];
            foreach ($this->files as $file) {
                if ($file instanceof UploadedFile) {
                    $filename = $this->uploadFile($file);
                    $this->imageManager->addImage($filename, $this->defaultCrop[0], $this->defaultCrop[1]);
                    $filenames[] = $filename;
                }
            }
            if (!$this->deleteOldImages) {
                $oldAttributes = $model->getOldAttribute($this->attributeName);
                $filenames = array_merge($filenames, explode(';', $oldAttributes));
            }
            $this->deleteFiles();
            $model->setAttributes([$this->attributeName => trim(implode(';', $filenames), ';')]);
            $this->protectOldValue = false;
        }
    }

    public function uploadFile($file)
    {
        if (!$file instanceof UploadedFile) {
            return;
        }
        $fileName = $file->name;
        if (!is_dir($this->savePath) && !mkdir($concurrentDirectory = $this->savePath, 0755, true) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        if ($this->generateNewName !== false) {
            $fileName = $this->generateNewName instanceof \Closure
                ? call_user_func($this->generateNewName, $fileName) . '.' . $file->getExtension()
                : $this->generateFileName($file);
            $file->name = $fileName;
        }

        $this->defaultCrop($file, $fileName, $this->owner);
        $this->crop($file, $fileName, $this->owner);
        return $fileName;
    }

    public function deleteFiles()
    {
        if (!$this->deleteOldImages) {
            return;
        }
        $model = $this->owner;
        if (!$oldFileNames = $model->getOldAttribute($this->attributeName)) {
            return;
        }
        $oldFileNames = explode(';', $oldFileNames);

        foreach ($oldFileNames as $oldFileName) {
            $filePath = $this->savePath . DIRECTORY_SEPARATOR . $oldFileName;
            if (is_file($filePath)) {
                unlink($filePath);
            }
            $this->imageManager->removeImage($oldFileName);
            if ($this->crop !== false) {
                foreach ($this->crop as $item) {
                    if (isset($item[2])) {
                        $filePath = $this->savePath . DIRECTORY_SEPARATOR . $item[2] . '_' . $oldFileName;
                        if (is_file($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }
        }
    }

    protected function generateFileName(UploadedFile $file)
    {
        return uniqid('', false) . '.' . $file->getExtension();
    }

    protected function defaultCrop($file, $fileName, $model)
    {
        if ($this->defaultCrop !== false && isset($this->defaultCrop[0]) && isset($this->defaultCrop[1])) {
            $manager = new ImageManager(array('driver' => 'gd'));
            $img = $manager->make($file->tempName);
            if ($this->cropCoordinatesAttrName && $model->{$this->cropCoordinatesAttrName}) {
                $cropOptions = json_decode($model->{$this->cropCoordinatesAttrName}, true);
                $cropOptionsDef = $cropOptions['defaultCrop'];
                $img->crop($cropOptionsDef['width'], $cropOptionsDef['height'], $cropOptionsDef['x'], $cropOptionsDef['y']);
                $this->cropWidenOrFit($this->defaultCrop[0], $this->defaultCrop[1], $img);
            } else {
                $this->cropWidenOrFit($this->defaultCrop[0], $this->defaultCrop[1], $img);
            }
            if ($this->watermark) {
                $watermark = \Intervention\Image\ImageManagerStatic::make($this->watermark);
                $watermark->resize(300, null, static function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->insert($watermark, 'center');
            }
            $img->save($this->savePath . DIRECTORY_SEPARATOR . $fileName, 100);
        } else {
            $file->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName, 100);
        }
    }

    protected function crop($file, $fileName, $model)
    {
        if ($this->crop !== false && isset($this->defaultCrop[0]) && isset($this->defaultCrop[1])) {
            if (is_array($this->crop)) {
                $manager = new ImageManager(array('driver' => 'gd'));
                foreach ($this->crop as $item) {
                    if (!isset($item[0]) && !isset($item[1]) && !isset($item[2])) {
                        throw new ServerErrorHttpException('UploadFileBehavior: Задайте ширину , высоту и prefix для crop (измените либо в модели, либо в конфигурационном файле [850,450,"prefix"].)');
                    }
                    if ($this->cropCoordinatesAttrName && $model->{$this->cropCoordinatesAttrName}) {
                        $cropOptions = json_decode($model->{$this->cropCoordinatesAttrName}, true);
                        $img = $manager->make($file->tempName);
                        $img->crop($cropOptions[$item[2]]['width'],
                            $cropOptions[$item[2]]['height'],
                            $cropOptions[$item[2]]['x'],
                            $cropOptions[$item[2]]['y']
                        );
                        $this->cropWidenOrFit($item[0], $item[1], $img);
                    } else {
                        $img = $manager->make($file->tempName);
                        $this->cropWidenOrFit($item[0], $item[1], $img);
                    }
                    $img->save($this->savePath . DIRECTORY_SEPARATOR . $item[2] . '_' . $fileName, 100);
                }
            }
        }
    }

    protected function cropWidenOrFit($w, $h, $img)
    {
        if ($w == 0 && $h == 0)
            throw new ServerErrorHttpException('UploadFileBehavior: Ширина и высота одновременно не могу быть равны 0 (измените либо в модели, либо в конфигурационном файле).)');
        elseif ($w !== 0 && $h !== 0) {
            $img->fit($w, $h);
        } elseif ($h == 0)
            $img->widen($w, function ($constraint) {
                $constraint->upsize();
            });
        else $img->heighten($h);
    }
}