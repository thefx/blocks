<?php

use yii\db\Migration;

/**
 * Class m190206_174246_1
 */
class m190206_174246_create_block_translate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_translate}}', [
            'id'  => $this->primaryKey(),
            'block_id'  => $this->integer()->notNull(),
            'category' => $this->string(),
            'categories' => $this->string(),
            'block_item' => $this->string(),
            'blocks_item' => $this->string(),
            'block_create' => $this->string(),
            'block_update' => $this->string(),
            'block_delete' => $this->string(),
            'category_create' => $this->string(),
            'category_update' => $this->string(),
            'category_delete' => $this->string(),
        ], $tableOptions);

        $this->createIndex('block_translate_block_id', '{{%block_translate}}', ['block_id'], true);

//        // demo
//        $this->insert('{{%block_translate}}', [
//            'id'  => 1,
//            'block_id'  => 1,
//            'category' => 'Раздел',
//            'categories' => 'Разделы',
//            'block_item' => 'Элемент',
//            'blocks_item' => 'Элементы',
//            'block_create' => 'Добавить статью',
//            'block_update' => 'Изменить статью',
//            'block_delete' => 'Удалить статью',
//            'category_create' => 'Добавить раздел',
//            'category_update' => 'Изменить раздел',
//            'category_delete' => 'Удалить раздел',
//        ]);
    }

//    const title_category = 'коттеджные поселки';
//    const title_categories = 'коттеджные поселки';
//
//    const title_block = 'редактирование';
//    const title_blocks = 'новая категория';
//
//    const block_create = 'добавить блок';
//    const block_update = 'редактировать блок';
//    const block_delete = 'удалить блок';
//
//    const category_create = 'добавить категорию';
//    const category_update = 'редактировать категорию';
//    const category_delete = 'удалить категорию';

//    Разделы:Разделы
//    Раздел:Раздел

//    Добавить раздел:Добавить раздел
//    Изменить раздел:Изменить раздел
//    Удалить раздел:Удалить раздел

//    Элементы:Статьи
//    Элемент:Статья

//    Добавить элемент:Добавить статью
//    Изменить элемент:Изменить статью
//    Удалить элемент:Удалить статью

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_translate}}');
    }
}
