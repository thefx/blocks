<?php

use yii\db\Migration;

/**
 * Class m200229_132347_1
 */
class m200229_132347_create_sort_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%block_category}}', 'sort', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%block_category}}', 'sort');
    }
}
