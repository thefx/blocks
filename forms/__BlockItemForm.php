<?php

namespace thefx\blocks\forms;

use app\shop\services\TransactionManager;
use thefx\blocks\models\blocks\BlockItem;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class BlockItemForm extends Model
{
    public $title;
    public $anons;
    public $text;
    public $path;
    public $date;
    public $parent_id;
    public $public;
    public $sort;

    public $photo_preview;
    public $photo;

    public $photo_preview_crop;     // for crop
    public $photo_crop;             // for crop

    /**
     * @var BlockItem
     */
    private $model;
    /**
     * @var TransactionManager
     */
    private $transaction;

    public function __construct(BlockItem $model = null, $parent_id = null, $config = [])
    {
        if ($model) {
            $this->setAttributes($model->getAttributes());
            $this->model = $model;
        } else {
            $this->parent_id = $parent_id;
            $this->sort = 100;
        }
        $this->transaction = \Yii::createObject(TransactionManager::class);

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['text'], 'string'],
            [['date'], 'safe'],
            [['parent_id'], 'required'],
            [['parent_id', 'public', 'sort'], 'integer'],
            [['title', 'path', 'anons', 'photo_crop', 'photo_preview_crop'], 'string', 'max' => 255],
            [['photo', 'photo_preview'], 'file', 'mimeTypes' => 'image/*'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
//        $this->model->setAttributes($this->getAttributes());
        $this->model->load(\Yii::$app->request->post());
        return $this->model->save();
    }

    public function getPhoto($attribute)
    {
        return $this->model->getPhoto($attribute);
    }

    public function categoryList()
    {
        $category = BlockCategory::findOne(['id' => $this->parent_id]);
        $categories = BlockCategory::find()->where(['block_id' => $category->block_id])->orderBy('lft')->all();

        $arr =  ArrayHelper::map($categories, 'id', function($row) {
            return str_repeat('—', $row->depth) . ' ' . $row->title;
        });

        return $arr;
    }

    public function getEditorPath()
    {
        $id = $this->model->getPrimaryKey();
        $path = 'block';

        $dir = \Yii::getAlias('@webroot/upload/' . $path) . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return \Yii::getAlias('@web/upload/' . $path) . '/';
    }

    public function getIsNewRecord()
    {
        return $this->model === null;
    }
}