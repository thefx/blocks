<?php

namespace thefx\blocks\models\forms;

use thefx\blocks\models\Block;
use thefx\blocks\models\BlockFields;
use thefx\blocks\traits\TransactionTrait;
use Yii;
use yii\base\Model;

class BlockFieldsForm extends Model
{
    public $template;
    public $type;
    public $value;
    public $parent_id;
    public $sort;
    public $block_type;

    use TransactionTrait;

    /**
     * @var Block
     */
    protected $block;

    public function __construct(Block $block, $block_type, $config = [])
    {
        $this->block = $block;
        $this->block_type = $block_type;
        $template = $block_type === BlockFields::TYPE_BLOCK_ITEM
            ? $this->block->getFieldsTemplates(true)
            : $this->block->getFieldsCategoryTemplates();

        $this->template = $this->prettyJson($template);

        parent::__construct($config);
    }

    public function prettyJson(array $template)
    {
        $template = json_encode($template, JSON_UNESCAPED_UNICODE);
        $template = str_replace('[', "[\r", $template);
        $template = str_replace('}]', "}\r]", $template);
        $template = str_replace('}[', "}\r\r[", $template);
        $template = str_replace('],"', "],\r\"", $template);
        $template = str_replace('},{', "},\r{", $template);

        return $template;
    }

    public function getDefaultTemplate()
    {
        $template = $this->block_type === BlockFields::TYPE_BLOCK_ITEM
            ? $this->block->getDefaultFieldsTemplates()
            : $this->block->getDefaultFieldsCategoryTemplates();

        return $this->prettyJson($template);
    }

    public function rules()
    {
        return [
            [['template', 'type', 'value', 'block_type'], 'string'],
            [['parent_id', 'sort'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->wrap(function () {

            BlockFields::deleteAll(['block_id' => $this->block->id, 'block_type' => $this->block_type]);

            $template = json_decode($this->template, false);

            if ($template === null) {
                return true;
            }

            $sortGroups = 0;
            $childrenArr = [];

            if ($this->block_type === BlockFields::TYPE_BLOCK_ITEM) {
                foreach ($template as $groupName => $children) {
                    $field = BlockFields::createGroup($this->block->id, $this->block_type, $groupName, $sortGroups++);
                    $field->save();

                    foreach ($children as $k => $child) {
                        $childrenArr[] = [
                            'block_id' => $this->block->id,
                            'block_type' => $this->block_type,
                            'parent_id' => $field->id,
                            'type' => $child->type,
                            'value' => $child->value,
                            'sort' => $k,
                        ];
                    }
                }
            } else {
                foreach ($template as $k => $child) {
                    $childrenArr[] = [
                        'block_id' => $this->block->id,
                        'block_type' => $this->block_type,
                        'parent_id' => 0,
                        'type' => $child->type,
                        'value' => $child->value,
                        'sort' => $k,
                    ];
                }
            }

            if (!empty($childrenArr)) {
                Yii::$app->db->createCommand()
                    ->batchInsert(BlockFields::tableName(), array_keys(reset($childrenArr)), $childrenArr)
                    ->execute();
            }
        });

        return $this->block;
    }

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }
}