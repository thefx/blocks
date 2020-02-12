<?php

use yii\db\Migration;

/**
 * Handles the creation of table `block_info`.
 */
class m190204_192026_create_block_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'path' => $this->string(),
            'table' => $this->string(),
            'template' => $this->string(),
            'pagination' => $this->string(),
            'create_user' => $this->integer(),
            'create_date' => $this->dateTime(),
            'update_user' => $this->integer(),
            'update_date' => $this->dateTime(),
        ], $tableOptions);

//        // demo
//        $this->insert('{{%block}}', [
//            'id' => 1,
//            'title' => 'demo',
//            'path' => 'demo',
//            'table' => null,
//            'template' => null,
//            'pagination' => null,
//            'create_user' => 1,
//            'create_date' => date('Y-m-d H:i:s'),
//            'update_user' => 1,
//            'update_date' => date('Y-m-d H:i:s'),
//        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block}}');
    }
}
