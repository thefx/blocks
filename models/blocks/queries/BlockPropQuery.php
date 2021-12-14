<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockProp;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockProp]].
 *
 * @see BlockProp
 */
class BlockPropQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return BlockProp[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BlockProp|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
