<?php

use yii\db\Migration;

/**
 * Class m190405_143409_1
 */
class m190405_143409_create_block_fields_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_fields}}', [
            'id' => $this->primaryKey(),
            'block_id' => $this->integer(),
            'type' => $this->string(10),
            'value' => $this->string(255),
            'name' => $this->string(255),
            'parent_id' => $this->integer(),
            'sort' => $this->integer(),
            'block_type' => $this->string(10),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_fields}}');
    }
}
