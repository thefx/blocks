<?php

namespace thefx\blocks\services;

use thefx\blocks\models\blocks\BlockProp;

class BlockItemPropertyAssignmentService
{
    public function splitAndStore($blockItemId = null)
    {
        $query = (new \yii\db\Query())
            ->select('a.id, a.block_item_id, a.prop_id, p.type, a.value, i.title as block_item_title')
            ->from('block_item_prop_assignments a')
            ->leftJoin('block_prop p', 'p.id = a.prop_id')
            ->leftJoin('block_item i', 'i.id = a.block_item_id')
            ->where('i.id is not null and p.id is not null')
            ->andFilterWhere(['a.block_item_id' => $blockItemId]);

        $command = $query->createCommand();
        $oldAssignments = $command->queryAll();

        $newAssignments = [];

        foreach ($oldAssignments as $oldAssignment) {
            // need to split
            if ($oldAssignment['type'] === BlockProp::TYPE_LIST ||
                $oldAssignment['type'] === BlockProp::TYPE_FILE ||
                $oldAssignment['type'] === BlockProp::TYPE_RELATIVE_BLOCK_CAT ||
                $oldAssignment['type'] === BlockProp::TYPE_RELATIVE_BLOCK_ITEM ||
                $oldAssignment['type'] === BlockProp::TYPE_IMAGE)
            {
                foreach (explode(';', $oldAssignment['value']) as $value) {
                    $newAssignments[] = [
                        'block_item_id' => $oldAssignment['block_item_id'],
                        'prop_id' => $oldAssignment['prop_id'],
                        'value' => $value,
                    ];
                }
            } else {
                $newAssignments[] = [
                    'block_item_id' => $oldAssignment['block_item_id'],
                    'prop_id' => $oldAssignment['prop_id'],
                    'value' => $oldAssignment['value'],
                ];
            }
        }

        $newAssignmentsChunks = array_chunk($newAssignments, 500);

        if ($blockItemId) {
            \Yii::$app->db->createCommand()->delete('block_item_prop_assignments_2', ['block_item_id' => $blockItemId])->execute();
        } else {
            \Yii::$app->db->createCommand()->delete('block_item_prop_assignments_2')->execute();
        }

        foreach ($newAssignmentsChunks as $newAssignmentsChunk) {
            \Yii::$app->db->createCommand()->batchInsert('block_item_prop_assignments_2', ['block_item_id', 'prop_id', 'value'], $newAssignmentsChunk)->execute();
        }

        return $newAssignments;
    }
}