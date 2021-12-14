<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockSeo;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockSeo]].
 *
 * @see BlockSeo
 */
class BlockSeoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return BlockSeo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return BlockSeo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
