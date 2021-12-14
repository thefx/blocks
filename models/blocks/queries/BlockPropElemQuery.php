<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockPropElem;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockPropElem]].
 *
 * @see BlockPropElem
 */
class BlockPropElemQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return BlockPropElem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BlockPropElem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
