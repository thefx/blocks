<?php

namespace thefx\blocks\models\blocks\queries;

use thefx\blocks\models\blocks\BlockItemPropAssignments;

/**
 * This is the ActiveQuery class for [[\app\shop\entities\Block\BlockItemPropAssignments]].
 *
 * @see BlockItemPropAssignments
 */
class BlockItemPropAssignmentsQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return BlockItemPropAssignments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return BlockItemPropAssignments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
