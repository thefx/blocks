<?php

use yii\db\Migration;

/**
 * Class m200227_084744_create_files_table
 */
class m200227_084744_create_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'file' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'size' => $this->integer()->defaultValue(0)->notNull(),
            'sort' => $this->integer()->defaultValue(0)->notNull(),
        ], $tableOptions);

        $this->createIndex('image_file', '{{%files}}', ['file'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
