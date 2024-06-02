<?php

namespace thefx\blocks\forms;

use thefx\blocks\models\blocks\BlockItem;
use thefx\blocks\models\blocks\BlockItemPropCompare;
use thefx\blocks\models\blocks\BlockProp;
use thefx\blocks\traits\TransactionTrait;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class BlockItemCompositeForm extends Model
{
//    TODO
//    /**
//     * @var BlockItem
//     */
//    public $model;
//    /**
//     * @var BlockItemPropAssignments[]
//     */
//    public $propAssignments = [];

    public $propCompareIds = [];

    private $_propsCompareList;

    public function rules()
    {
        return [
            [['propCompareIds'], 'each', 'rule' => ['integer']],
            [['propCompareIds'], 'default', 'value' => []],
        ];
    }

    use TransactionTrait;

    public function __construct(BlockItem $blockItem, $config = [])
    {
        $this->propCompareIds = BlockItemPropCompare::find()->select('prop_id')
            ->where(['item_id' => $blockItem->id])
            ->column();

        $propsCompare = BlockProp::find()->where(['block_id' => 1, 'compare' => 1])->all();
        $this->_propsCompareList = ArrayHelper::map($propsCompare, 'id', 'title');

        parent::__construct($config);
    }

    public function getPropsCompareList(): array
    {
        return $this->_propsCompareList;
    }
}