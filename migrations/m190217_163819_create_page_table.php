<?php

use yii\db\Migration;

/**
 * Class m190217_163819_1
 */
class m190217_163819_create_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'path' => $this->string(),
            'text' => $this->text(),
            'seo_title' => $this->string(),
            'seo_keywords' => $this->string(),
            'seo_description' => $this->string(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'create_user' => $this->integer(),
            'create_date' => $this->dateTime(),
            'update_user' => $this->integer(),
            'update_date' => $this->dateTime(),
            'public' => $this->integer(1)->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('page_lft_rgt', '{{%page}}', ['lft', 'rgt']);
        $this->createIndex('page_rgt', '{{%page}}', ['rgt']);
        $this->createIndex('page_parent_id_path', '{{%page}}', ['parent_id', 'path']);

        $this->insert('{{%page}}', [
            'id' => 1,
            'title' => 'Родительская категория',
            'text' => '',
            'path' => '',
            'parent_id' => 0,
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'create_user' => 1,
            'create_date' => date('Y-m-d H:i:s'),
            'update_user' => 1,
            'update_date' => date('Y-m-d H:i:s'),
            'public' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
