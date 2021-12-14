<?php

namespace thefx\blocks\forms;

use thefx\blocks\models\blocks\BlockFields;

class BlockFieldsItemForm extends BlockFieldsForm
{
    public function setBlockType()
    {
        $this->block_type = BlockFields::BLOCK_TYPE_ITEM;
    }

    public function setTextarea()
    {
        $this->textarea = json_encode($this->block->getFieldsTemplates(), JSON_UNESCAPED_UNICODE);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->block->fields = [];
        $this->block->save();

        $fields = $this->block->fields;

        $post = json_decode($this->textarea, false);

        if ($post === null) {
            return true;
        }

        foreach ($post as $groupName => $children) {

            $field = BlockFields::create($groupName, $this->block_type);

            $childrenArr = $field->children;
            foreach ($children as $k => $child) {
                $childrenArr[] = BlockFields::createChild($this->block->id, $child->type, $child->value, $k, $this->block_type);
            }
            $field->children = $childrenArr;

            $fields[] = $field;
        }

        $this->block->fields = $fields;
        $this->block->save() or die(var_dump($this->block->getErrors()));

        return $this->block;
    }
}