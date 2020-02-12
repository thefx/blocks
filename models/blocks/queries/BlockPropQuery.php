<?php

namespace thefx\blocks\models\blocks\queries;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockProp]].
 *
 * @see \app\shop\entities\Block\BlockProp
 */
class BlockPropQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockProp[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockProp|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
