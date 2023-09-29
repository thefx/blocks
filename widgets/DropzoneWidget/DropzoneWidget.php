<?php

namespace thefx\blocks\widgets\DropzoneWidget;

use thefx\blocks\models\BlockFiles;
use thefx\blocks\models\BlockItemPropertyAssignment;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;

class DropzoneWidget extends BaseWidget
{
    /**
     * @var BlockItemPropertyAssignment|null The data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string|null The model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string|null The input name. This must be set if `model` and `attribute` are not set.
     */
    public $name;

    /**
     * @var string|null The input value.
     */
    public $value;

    /**
     * @var array The HTML attribute options for the input tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    public $acceptedFiles = ".jpg,.jpeg,.png";

    public $resizeWidth = 1200;

    public $resizeHeight = 1200;

    public $maxFiles = null;

    public $extraData = [];

    public $uploadUrl = '/content/add-file';

    /**
     * @var array todo
     */
    private $settings;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        $request = Yii::$app->getRequest();

        if ($request->enableCsrfValidation) {
            $this->settings['params'][$request->csrfParam] = $request->getCsrfToken();
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScripts();

        $models = BlockFiles::find()
            ->indexBy('id')
            ->where(['id' => $this->model->value])
            ->all();

        $files = [];

        if (is_array($this->model->value)) {
            // save sorting
            foreach ($this->model->value as $fileId) {
                $files[$fileId] = $models[$fileId];
            }
        }

        $files = $this->getFilesData($files);

        return $this->render('index', [
            'widgetId' => $this->getId(),
            'hasModel' => $this->hasModel(),
            'model' => $this->model,
            'attributeName' => $this->attribute,
            'files' => $files,

            // dropzone props
            'acceptedFiles' => $this->acceptedFiles,
            'resizeWidth' => $this->resizeWidth,
            'resizeHeight' => $this->resizeHeight,
            'maxFiles' => $this->maxFiles,
            'uploadUrl' => $this->uploadUrl,
            'extraData' => $this->extraData,
        ]);
    }

    public function getFilesData($data)
    {
        return array_map(static function(BlockFiles $row) {
            $attributes = $row->getAttributes();
            $attributes['photo_path'] = $row->path . $row->file_name;
            $attributes['photo_path_preview'] = $row->path . $row->file_name;
            $attributes['update_time'] = time();
            $attributes['name'] = $row->path . $row->file_name;
            return $attributes;
        }, array_values($data));
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }

    /**
     * Register widget asset.
     */
    protected function registerClientScripts()
    {
        $view = $this->getView();
        $asset = Yii::$container->get(DropzoneWidgetAsset::class);
        $asset::register($view);
    }
}
