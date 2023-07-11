<?php

namespace thefx\blocks\models\queries;

use paulzi\nestedsets\NestedSetsQueryTrait;
use thefx\blocks\models\BlockSections;

/**
 * This is the ActiveQuery class for [[thefx\blocks\models\BlockSections]].
 *
 * @property mixed $root
 *
 * @see BlockSections
 */
class BlockSectionsQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;

    public function isRoot()
    {
        return false;
    }

//    public function withoutRoot()
//    {
//        return $this->andWhere('[[depth]]!=0');
//    }
//
//    public function getRoot()
//    {
//        return $this->andWhere('[[section_id]]=0');
//    }
}
