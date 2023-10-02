<?php

use yii\db\Migration;

class m190204_192026_create_block_items_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_items}}', [
            'id' => $this->primaryKey(),
            'block_id'  => $this->integer()->null(),
            'title' => $this->string(),
            'alias' => $this->string(),
            'anons' => $this->text(),
            'text' => $this->text(),
            'photo' => $this->string(),
            'photo_preview' => $this->string(),
            'date' => $this->date(),
            'section_id' => $this->integer()->notNull(),
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

        $this->createIndex('block_items_section_id_alias', '{{%block_items}}', ['section_id', 'alias']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_items}}');
    }
}
