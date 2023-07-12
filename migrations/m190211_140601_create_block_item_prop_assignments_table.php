<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%block_item_property_assignments}}`.
 */
class m190211_140601_create_block_item_prop_assignments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_item_prop_assignments}}', [
            'id' => $this->primaryKey(),
            'block_item_id' => $this->integer()->notNull(),
            'prop_id' => $this->integer()->notNull(),
            'value' => $this->text(),
        ], $tableOptions);

//        $this->createIndex('block_item_prop_assignments_block_item_id_prop_id', '{{%block_item_prop_assignments}}', ['block_item_id', 'prop_id'], true);

        // integer
//        $this->insert('{{%block_item_prop_assignments}}', [
//            'id' => 1,
//            'block_item_id' => 1,
//            'prop_id' => 1,
//            'value' => 123,
//        ]);
//        // list
//        $this->insert('{{%block_item_prop_assignments}}', [
//            'id' => 2,
//            'block_item_id' => 1,
//            'prop_id' => 2,
//            'value' => 1,
//        ]);
//        // file
//        $this->insert('{{%block_item_prop_assignments}}', [
//            'id' => 3,
//            'block_item_id' => 1,
//            'prop_id' => 3,
//            'value' => '123.jpg',
//        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_item_prop_assignments}}');
    }
}
