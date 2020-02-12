<?php

use yii\db\Migration;

/**
 * Class m190201_191732_1
 */
class m190201_191732_create_block_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_category}}', [
            'id' => $this->primaryKey(),
            'block_id'  => $this->integer()->null(),
            'title' => $this->string(),
            'path' => $this->string(),
            'anons' => $this->text(),
            'text' => $this->text(),
            'photo' => $this->string(),
            'photo_preview' => $this->string(),
            'date' => $this->date(),
            'parent_id' => $this->integer()->notNull()->defaultValue(1),
            'lft'   => $this->integer()->notNull(),
            'rgt'   => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(), // not unsigned!
            'seo_title' => $this->string(),
            'seo_keywords' => $this->string(),
            'seo_description' => $this->string(),
            'create_user' => $this->integer(),
            'create_date' => $this->dateTime(),
            'update_user' => $this->integer(),
            'update_date' => $this->dateTime(),
            'public' => $this->integer(1)->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('block_category_lft_rgt', '{{%block_category}}', ['block_id', 'lft', 'rgt']);
        $this->createIndex('block_category_rgt', '{{%block_category}}', ['block_id', 'rgt']);
        $this->createIndex('block_category_url_parent_id_path', '{{%block_category}}', ['parent_id', 'path']);

//        // demo
//        $this->insert('{{%block_category}}', [
//            'id' => 1,
//            'block_id'  => 1,
//            'title' => 'Родительская категория',
//            'path' => '',
//            'anons' => '',
//            'text' => '',
//            'photo' => null,
//            'photo_preview' => null,
//            'date' => date('Y-m-d H:i:s'),
//            'parent_id' => 0,
//            'lft'   => 1,
//            'rgt'   => 8,
//            'depth' => 0, // not unsigned!
//            'create_user' => 1,
//            'create_date' => date('Y-m-d H:i:s'),
//            'update_user' => 1,
//            'update_date' => date('Y-m-d H:i:s'),
//            'public' => 1,
//        ]);
//        $this->insert('{{%block_category}}', [
//            'id' => 2,
//            'block_id'  => 1,
//            'title' => 'папка 2',
//            'path' => '',
//            'anons' => 'It is a long established fact that a reader will be distracted',
//            'text' => 'It is a long established fact that a reader will be distracted It is a long established fact that a reader will be distracted',
//            'photo' => null,
//            'photo_preview' => null,
//            'date' => date('Y-m-d H:i:s'),
//            'parent_id' => 1,
//            'lft'   => 2,
//            'rgt'   => 5,
//            'depth' => 1, // not unsigned!
//            'create_user' => 1,
//            'create_date' => date('Y-m-d H:i:s'),
//            'update_user' => 1,
//            'update_date' => date('Y-m-d H:i:s'),
//            'public' => 1,
//        ]);
//        $this->insert('{{%block_category}}', [
//            'id' => 3,
//            'block_id'  => 1,
//            'title' => 'папка 3',
//            'path' => '',
//            'anons' => 'It is a long established fact that a reader will be distracted',
//            'text' => 'It is a long established fact that a reader will be distracted It is a long established fact that a reader will be distracted',
//            'photo' => null,
//            'photo_preview' => null,
//            'date' => date('Y-m-d H:i:s'),
//            'parent_id' => 2,
//            'lft'   => 3,
//            'rgt'   => 4,
//            'depth' => 2, // not unsigned!
//            'create_user' => 1,
//            'create_date' => date('Y-m-d H:i:s'),
//            'update_user' => 1,
//            'update_date' => date('Y-m-d H:i:s'),
//            'public' => 1,
//        ]);
//        $this->insert('{{%block_category}}', [
//            'id' => 4,
//            'block_id'  => 1,
//            'title' => 'папка 4',
//            'path' => '',
//            'anons' => 'It is a long established fact that a reader will be distracted',
//            'text' => 'It is a long established fact that a reader will be distracted It is a long established fact that a reader will be distracted',
//            'photo' => null,
//            'photo_preview' => null,
//            'date' => date('Y-m-d H:i:s'),
//            'parent_id' => 1,
//            'lft'   => 6,
//            'rgt'   => 7,
//            'depth' => 1, // not unsigned!
//            'create_user' => 1,
//            'create_date' => date('Y-m-d H:i:s'),
//            'update_user' => 1,
//            'update_date' => date('Y-m-d H:i:s'),
//            'public' => 1,
//        ]);

//        $node11 = new BlockCategory();
//        $node11->title = 'Родительская категория';
//        $node11->makeRoot()->save() or die(var_dump($node11->getErrors()));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_category}}');
    }
}
