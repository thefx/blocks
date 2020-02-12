<?php

namespace thefx\blocks\models\blocks\queries;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockPropElem]].
 *
 * @see \app\shop\entities\Block\BlockPropElem
 */
class BlockPropElemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockPropElem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockPropElem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
