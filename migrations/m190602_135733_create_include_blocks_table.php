<?php

use yii\db\Migration;

/**
 * Class m190602_135733_1
 */
class m190602_135733_create_include_blocks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%include_blocks}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text(),
            'position' => $this->string(50)->notNull(),
            'public' => $this->integer(1)->notNull()->defaultValue(1),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%include_blocks}}');
    }
}
