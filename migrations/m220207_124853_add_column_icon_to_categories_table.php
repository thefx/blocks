<?php

use yii\db\Migration;

/**
 * Class m220207_124853_add_column_icon_to_categories_table
 */
class m220207_124853_add_column_icon_to_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%block_category}}', 'icon', $this->text()->after('photo_preview'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%block_category}}', 'icon');
    }
}
