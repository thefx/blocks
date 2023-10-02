<?php

use yii\db\Migration;

class m190201_191732_create_block_sections_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_sections}}', [
            'id' => $this->primaryKey(),
            'block_id'  => $this->integer()->null(),
            'title' => $this->string(),
            'alias' => $this->string(),
            'anons' => $this->text(),
            'text' => $this->text(),
            'photo' => $this->string(),
            'photo_preview' => $this->string(),
            'sort' => $this->integer(),
            'seo_title' => $this->string(),
            'seo_keywords' => $this->string(),
            'seo_description' => $this->string(),
            'public' => $this->integer(1)->notNull()->defaultValue(0),
            'section_id' => $this->integer()->notNull()->defaultValue(1),
            'left'   => $this->integer()->notNull(),
            'right'   => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'create_user' => $this->integer(),
            'create_date' => $this->dateTime(),
            'update_user' => $this->integer(),
            'update_date' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex('block_sections_left_right', '{{%block_sections}}', ['block_id', 'left', 'right']);
        $this->createIndex('block_sections_right', '{{%block_sections}}', ['block_id', 'right']);
        $this->createIndex('block_sections_url_section_id_alias', '{{%block_sections}}', ['section_id', 'alias']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_sections}}');
    }
}
