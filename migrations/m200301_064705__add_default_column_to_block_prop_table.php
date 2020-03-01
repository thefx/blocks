<?php

use yii\db\Migration;

/**
 * Class m200301_064705_1
 */
class m200301_064705__add_default_column_to_block_prop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%block_prop}}', 'default_value', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%block_prop}}', 'default_value');
    }
}
