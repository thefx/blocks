<?php

use yii\db\Migration;

class m190206_181427_create_block_property_elements_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_property_elements}}', [
            'id'  => $this->primaryKey(),
            'property_id'  => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull()->defaultValue(100),
            'default' => $this->integer(1)->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('{{%idx-block_property_elements-block_property_id}}', '{{%block_property_elements}}', 'block_property_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_property_elements}}');
    }
}
