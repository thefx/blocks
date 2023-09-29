<?php

use yii\db\Migration;

class m190211_140601_create_block_item_property_assignments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_item_property_assignments}}', [
            'id' => $this->primaryKey(),
            'block_item_id' => $this->integer()->notNull(),
            'property_id' => $this->integer()->notNull(),
            'value' => $this->text(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_item_property_assignments}}');
    }
}
