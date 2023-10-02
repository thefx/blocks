<?php

use yii\db\Migration;

class m190206_175917_create_block_properties_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_properties}}', [
            'id'  => $this->primaryKey(),
            'block_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'type' => $this->string(20),
            'public' => $this->integer(1)->notNull()->defaultValue(1),
            'multiple' => $this->integer(1)->notNull()->defaultValue(0),
            'required' => $this->integer(1)->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(100),
            'code' => $this->string(),
            'file_type' => $this->string(),
            'with_description' => $this->integer(1),
            'hint' => $this->string(),
            'relative_item' => $this->integer(),
            'relative_category' => $this->integer(),
            'default_value' => $this->string(),
            'redactor' => $this->integer(1)->notNull()->defaultValue(0),
        ], $tableOptions);

//        $this->createIndex('block_properties_block_id_code', '{{%block_properties}}', ['block_id', 'code'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_properties}}');
    }
}
