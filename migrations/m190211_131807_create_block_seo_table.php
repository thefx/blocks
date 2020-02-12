<?php

use yii\db\Migration;

/**
 * Class m190211_131807_block_seo
 */
class m190211_131807_create_block_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_seo}}', [
            'id' => $this->primaryKey(),
            'block_id' => $this->integer()->notNull(),
            'item_title' => $this->string(),
            'item_keywords' => $this->string(),
            'item_description' => $this->string(),
            'category_title' => $this->string(),
            'category_keywords' => $this->string(),
            'category_description' => $this->string(),
        ], $tableOptions);

        $this->createIndex('block_seo_block_id', '{{%block_seo}}', ['block_id'], true);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_seo}}');
    }
}
