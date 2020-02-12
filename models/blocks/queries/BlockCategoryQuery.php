<?php

namespace thefx\blocks\models\blocks\queries;

use paulzi\nestedsets\NestedSetsQueryTrait;

/**
 * This is the ActiveQuery class for [[thefx\blocks\models\blocks\BlockCategory]].
 *
 * @property mixed $root
 *
 * @see thefx\blocks\models\blocks\BlockCategory
 */
class BlockCategoryQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function withOutRoot()
    {
        return $this->andWhere('[[id]]!=1');
    }

    public function getRoot()
    {
        return $this->andWhere('[[parent_id]]=0');
    }

}
