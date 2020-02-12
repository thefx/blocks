<?php

use yii\db\Migration;

/**
 * Class m190206_181427_1
 */
class m190206_181427_create_block_prop_elem_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_prop_elem}}', [
            'id'  => $this->primaryKey(),
            'block_prop_id'  => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull()->defaultValue(100),
            'default' => $this->integer(1)->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('{{%idx-block_prop_elem-block_prop_id}}', '{{%block_prop_elem}}', 'block_prop_id');

//        // demo
//        $this->insert('{{%block_prop_elem}}', [
//            'id'  => 1,
//            'block_prop_id'  => 2,
//            'title' => 'Option 1',
//            'code' => 'OPTION1',
//            'sort' => 100,
//            'default' => 1,
//        ]);
//        $this->insert('{{%block_prop_elem}}', [
//            'id'  => 2,
//            'block_prop_id'  => 2,
//            'title' => 'Option 2',
//            'code' => 'OPTION2',
//            'sort' => 100,
//            'default' => 0,
//        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_prop_elem}}');
    }
}
