<?php
namespace thefx\blocks\behaviours;

use thefx\blocks\models\files\Files;
use Yii;
use yii\base\Behavior;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;

/**
 * @property ActiveRecord $owner
 */
class UploadFileBehavior extends Behavior
{
    /** @var string model file field name */
    public $attributeName = '';

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
     * @var bool erase protection the old value of the model attribute if the form returns empty string
     */
    public $protectOldValue = true;

    public $deleteOldFiles = false;

    /**
     * @var UploadedFile[]
     */
    protected $files;

    protected $filesManager;

    public function __construct(Files $filesManager, array $config = [])
    {
        $this->filesManager = $filesManager;
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
            $model->setAttribute($this->attributeName, $this->files);
            return true;
        }

        if (!$model->getAttribute($this->attributeName) instanceof yii\web\UploadedFile && $file = UploadedFile::getInstance($model, $this->attributeName)) {
            $this->files = [$file];
            $model->setAttribute($this->attributeName, $file);
            return true;
        }

        $this->files = [$model->getAttribute($this->attributeName)];
        return true;
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
            $model->setAttribute($this->attributeName, $model->getOldAttribute($this->attributeName));
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
                    $this->filesManager->create($filename)->save() or die(var_dump($model->errors));
                    $filenames[] = $filename;
                }
            }
            if (!$this->deleteOldFiles) {
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

        $file->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);

        return $fileName;
    }

    public function deleteFiles()
    {
        if (!$this->deleteOldFiles) {
            return;
        }
        $model = $this->owner;
        if (!$oldFileNames = $model->getOldAttribute($this->attributeName)) {
            return;
        }
        $oldFileNames = explode(';', $oldFileNames);

        foreach ($oldFileNames as $oldFileName) {
            $filePath = $this->savePath . DIRECTORY_SEPARATOR . $oldFileName;
            if (is_file($filePath)) {unlink($filePath);}
            $this->filesManager->removeFile($oldFileName);
        }
    }

    protected function generateFileName(UploadedFile $file)
    {
        return uniqid('', false) . '.' . $file->getExtension();
    }
}
