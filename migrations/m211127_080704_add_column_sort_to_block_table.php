<?php

use yii\db\Migration;

/**
 * Class m211127_080704_add_column_sort_to_block_table
 */
class m211127_080704_add_column_sort_to_block_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%block}}', 'sort', $this->integer(5)->after('pagination')->defaultValue(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%block}}', 'sort');
    }
}
