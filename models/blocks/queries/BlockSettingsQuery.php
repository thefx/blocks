<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockSettings;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockSettings]].
 *
 * @see BlockSettings
 */
class BlockSettingsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\shop\entities\Block\BlockSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
