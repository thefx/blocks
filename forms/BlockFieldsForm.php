<?php

namespace thefx\blocks\forms;

use app\shop\services\TransactionManager;
use thefx\blocks\models\blocks\Block;
use Yii;
use yii\base\Model;

abstract class BlockFieldsForm extends Model
{
    public $textarea;
    public $type;
    public $value;
    public $parent_id;
    public $sort;
    public $block_type;

    /**
     * @var Block
     */
    protected $block;
    /**
     * @var TransactionManager
     */
    private $transaction;

    public function __construct(Block $block = null, $parent_id = null, $config = [])
    {
        if ($block) {
            $this->block = $block;
            $this->setTextarea();
        }
        $this->setBlockType();
        $this->transaction = Yii::createObject(TransactionManager::class);

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['textarea', 'type', 'value', 'block_type'], 'string'],
            [['parent_id', 'sort'], 'integer'],
        ];
    }

    abstract public function setTextarea();

    abstract public function setBlockType();

    abstract public function save();

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }
}