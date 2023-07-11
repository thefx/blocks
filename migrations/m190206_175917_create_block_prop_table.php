<?php

use yii\db\Migration;

/**
 * Class m190206_175917_1
 */
class m190206_175917_create_block_prop_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_prop}}', [
            'id'  => $this->primaryKey(),
            'block_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'type' => $this->string(20),
            'public' => $this->integer(1)->notNull()->defaultValue(1),
            'multi' => $this->integer(1)->notNull()->defaultValue(0),
            'required' => $this->integer(1)->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(100),
            'code' => $this->string(),
            'in_filter' => $this->integer(1)->notNull()->defaultValue(0),
            'hint' => $this->string(),
            'relative_item' => $this->integer(),
            'redactor' => $this->integer(1)->notNull()->defaultValue(0),
        ], $tableOptions);

//        $this->createIndex('block_prop_block_id_code', '{{%block_prop}}', ['block_id', 'code'], true);

//        // demo
//        $this->insert('{{%block_prop}}', [
//            'id'  => 1,
//            'block_id' => 1,
//            'title' => 'example integer',
//            'type' => 'int',
//            'public' => 1,
//            'multi' => 0,
//            'required' => 0,
//            'sort' => 100,
//            'code' => 'INTEGER_EXAMPLE',
//            'in_filter' => 1,
//            'hint' => 'It is a long established fact that a reader will be distracted',
//        ]);
//        $this->insert('{{%block_prop}}', [
//            'id'  => 2,
//            'block_id' => 1,
//            'title' => 'example list',
//            'type' => 'list',
//            'public' => 1,
//            'multi' => 0,
//            'required' => 0,
//            'sort' => 100,
//            'code' => 'LIST_EXAMPLE',
//            'in_filter' => 1,
//            'hint' => 'It is a long established fact that a reader will be distracted',
//        ]);
//        $this->insert('{{%block_prop}}', [
//            'id'  => 3,
//            'block_id' => 1,
//            'title' => 'example file',
//            'type' => 'file',
//            'public' => 1,
//            'multi' => 0,
//            'required' => 0,
//            'sort' => 100,
//            'code' => 'FILE_EXAMPLE',
//            'in_filter' => 1,
//            'hint' => 'It is a long established fact that a reader will be distracted',
//        ]);
//        $this->insert('{{%block_prop}}', [
//            'id'  => 4,
//            'block_id' => 1,
//            'title' => 'example int 2',
//            'type' => 'int',
//            'public' => 1,
//            'multi' => 0,
//            'required' => 1,
//            'sort' => 100,
//            'code' => 'INTEGER_EXAMPLE2',
//            'in_filter' => 1,
//            'hint' => 'It is a long established fact that a reader will be distracted',
//        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_prop}}');
    }
}
