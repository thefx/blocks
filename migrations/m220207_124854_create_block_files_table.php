<?php

use yii\db\Migration;

/**
 * Class m220207_124854_create_block_files_table
 */
class m220207_124854_create_block_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_files}}', [
            'id'  => $this->primaryKey(),
            'height'  => $this->integer(),
            'width'  => $this->integer(),
            'size'  => $this->bigInteger(),
            'path'  => $this->string(),
            'file_name'  => $this->string(),
            'description'  => $this->string(),
            'create_date' => $this->dateTime(),
            'create_user' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_files}}');
    }
}
