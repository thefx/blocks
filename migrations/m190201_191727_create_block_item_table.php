<?php

use yii\db\Migration;

/**
 * Class m190201_191727_1
 */
class m190201_191727_create_block_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_item}}', [
            'id' => $this->primaryKey(),
            'block_id'  => $this->integer()->null(),
            'title' => $this->string(),
            'path' => $this->string(),
            'anons' => $this->text(),
            'text' => $this->text(),
            'photo' => $this->string(),
            'photo_preview' => $this->string(),
            'date' => $this->date(),
            'parent_id' => $this->integer()->notNull(),
            'public' => $this->integer(1)->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(100),
            'seo_title' => $this->string(),
            'seo_keywords' => $this->string(),
            'seo_description' => $this->string(),
            'create_user' => $this->integer(),
            'create_date' => $this->dateTime(),
            'update_user' => $this->integer(),
            'update_date' => $this->dateTime(),
        ], $tableOptions);

//        $this->createIndex('block_item_path', '{{%block_item}}', ['path']);
        $this->createIndex('block_item_parent_id_path', '{{%block_item}}', ['parent_id', 'path']);

//        // demo
//        $this->insert('{{%block_item}}', [
//            'id' => 1,
//            'title' => 'material 1',
//            'path' => '',
//            'anons' => 'It is a long established fact that a reader will be distracted',
//            'text' => 'It is a long established fact that a reader will be distracted It is a long established fact that a reader will be distracted',
//            'photo' => null,
//            'photo_preview' => null,
//            'date' => date('Y-m-d H:i:s'),
//            'parent_id' => 1,
//            'public' => 1,
//            'sort' => 100,
//            'create_user' => 1,
//            'create_date' => date('Y-m-d H:i:s'),
//            'update_user' => 1,
//            'update_date' => date('Y-m-d H:i:s'),
//        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_item}}');
    }
}
