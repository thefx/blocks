<?php

use yii\db\Migration;

class m190206_174246_create_block_translates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_translates}}', [
            'id'  => $this->primaryKey(),
            'block_id'  => $this->integer()->notNull(),
            'category' => $this->string(),
            'categories' => $this->string(),
            'block_item' => $this->string(),
            'blocks_item' => $this->string(),
            'block_create' => $this->string(),
            'block_update' => $this->string(),
            'block_delete' => $this->string(),
            'category_create' => $this->string(),
            'category_update' => $this->string(),
            'category_delete' => $this->string(),
        ], $tableOptions);

        $this->createIndex('block_translate_block_id', '{{%block_translates}}', ['block_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_translates}}');
    }
}
