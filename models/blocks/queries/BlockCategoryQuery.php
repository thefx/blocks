<?php

namespace thefx\blocks\models\blocks\queries;

use paulzi\nestedsets\NestedSetsQueryTrait;

/**
 * This is the ActiveQuery class for [[thefx\blocks\models\blocks\BlockCategory]].
 *
 * @property mixed $root
 *
 * @see BlockCategory
 */
class BlockCategoryQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;

    public function withoutRoot()
    {
        return $this->andWhere('[[depth]]!=0');
    }

    public function getRoot()
    {
        return $this->andWhere('[[parent_id]]=0');
    }

}
