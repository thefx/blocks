<?php

namespace thefx\blocks\forms;

use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockFields;
use thefx\blocks\models\blocks\BlockItem;
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

        switch ($block_type) {
            case BlockFields::BLOCK_TYPE_ITEM:
                $template = $this->block->getFieldsTemplates(BlockItem::TYPE_ITEM);
                break;
            case BlockFields::BLOCK_TYPE_SERIES:
                $template = $this->block->getFieldsTemplates(BlockItem::TYPE_SERIES);
                break;
            case BlockFields::BLOCK_TYPE_CATEGORY:
                $template = $this->block->getFieldsCategoryTemplates();
                break;
            default:
               die("Unknown block type: {$block_type}");
        }

        $this->template = $this->prettyJson($template);

        parent::__construct($config);
    }

    public function prettyJson(array $template)
    {
        return str_replace(
            ['[',       '}]',   '}[',     '],"',    '},{',       '","' ],
            ["[\r    ", "}\r]", "}\r\r[", "],\r\"", "},\r    {", '", "'],
            json_encode($template, JSON_UNESCAPED_UNICODE));
    }

    public function getDefaultTemplate()
    {
        switch ($this->block_type) {
            case BlockFields::BLOCK_TYPE_ITEM:
                $template = $this->block->getDefaultFieldsTemplates();
                break;
            case BlockFields::BLOCK_TYPE_SERIES:
                $template = $this->block->getDefaultFieldsSeriesTemplates();
                break;
            case BlockFields::BLOCK_TYPE_CATEGORY:
                $template = $this->block->getDefaultFieldsCategoryTemplates();
                break;
            default:
                die("Unknown block type: {$this->block_type}");
        }

        return $this->prettyJson($template);
    }

    public function rules()
    {
        return [
            [['template', 'type', 'value', 'block_type'], 'string'],
            [['parent_id', 'sort'], 'integer'],
            ['block_type', 'in', 'range' => [
                BlockFields::BLOCK_TYPE_ITEM,
                BlockFields::BLOCK_TYPE_SERIES,
                BlockFields::BLOCK_TYPE_CATEGORY]
            ]
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

            switch ($this->block_type) {
                case BlockFields::BLOCK_TYPE_ITEM:
                case BlockFields::BLOCK_TYPE_SERIES:
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
                                'name' => $child->name,
                                'sort' => $k,
                            ];
                        }
                    }
                    break;
                case BlockFields::BLOCK_TYPE_CATEGORY:
                    foreach ($template as $k => $child) {
                        $childrenArr[] = [
                            'block_id' => $this->block->id,
                            'block_type' => $this->block_type,
                            'parent_id' => 0,
                            'type' => $child->type,
                            'value' => $child->value,
                            'name' => $child->name,
                            'sort' => $k,
                        ];
                    }
                    break;
                default:
                    die("Unknown block type: {$this->block_type}");
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