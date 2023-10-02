<?php

use yii\db\Migration;

class m190201_191727_create_blocks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%blocks}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'alias' => $this->string(),
            'sort' => $this->integer(5)->defaultValue(20)->defaultValue(100),
            'create_user' => $this->integer(),
            'create_date' => $this->dateTime(),
            'update_user' => $this->integer(),
            'update_date' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blocks}}');
    }
}
