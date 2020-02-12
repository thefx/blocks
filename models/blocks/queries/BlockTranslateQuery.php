<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockTranslate;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockTranslate]].
 *
 * @see BlockTranslate
 */
class BlockTranslateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockTranslate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockTranslate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
