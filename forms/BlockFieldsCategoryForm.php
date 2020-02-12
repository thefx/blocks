<?php

namespace thefx\blocks\forms;

use thefx\blocks\models\blocks\BlockFields;

class BlockFieldsCategoryForm extends BlockFieldsForm
{
    public function setBlockType()
    {
        $this->block_type = BlockFields::BLOCK_TYPE_CATEGORY;
    }

    public function setTextarea()
    {
        $this->textarea = json_encode($this->block->getFieldsCategoryTemplates(), JSON_UNESCAPED_UNICODE);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->block->fieldsCategory = [];
        $this->block->save();
        $fields = [];

        if (($post = json_decode($this->textarea)) === null) {
            return true;
        }
        foreach ($post as $k => $field) {
            $fields[] = BlockFields::createChild($this->block->id, $field->type, $field->value, $k, $this->block_type);
        }
        $this->block->fieldsCategory = $fields;
        $this->block->save() or die(var_dump($this->block->getErrors()));

        return $this->block;
    }
}