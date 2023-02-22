<?php

use yii\db\Migration;

/**
 * Class m190217_163814_create_image_table
 */
class m190217_163814_create_image_table extends Migration
{
//    /**
//     * @inheritdoc
//     */
//    public function safeUp()
//    {
//        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
//
//        $this->createTable('{{%image}}', [
//            'id' => $this->primaryKey(),
//            'dir' => $this->string()->notNull()->defaultValue('/upload/blocks/'),
//            'file' => $this->string()->notNull(),
//            'title' => $this->string()->notNull(),
//            'width' => $this->integer()->defaultValue(0)->notNull(),
//            'height' => $this->integer()->defaultValue(0)->notNull(),
//            'size' => $this->integer()->defaultValue(0)->notNull(),
//            'sort' => $this->integer()->defaultValue(0)->notNull(),
//            'create_user' => $this->integer(),
//            'create_date' => $this->integer(),
//        ], $tableOptions);
//
//        $this->createIndex('image_file', '{{%image}}', ['file'], true);
//    }
//    /**
//     * {@inheritdoc}
//     */
//    public function safeDown()
//    {
//        $this->dropTable('{{%image}}');
//    }
}
