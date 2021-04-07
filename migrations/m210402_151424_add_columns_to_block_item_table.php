<?php

use yii\db\Migration;

/**
 * Class m210402_151424_1
 */
class m210402_151424_add_columns_to_block_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%block_item}}', 'article', $this->string(50));
        $this->addColumn('{{%block_item}}', 'price', $this->float());
        $this->addColumn('{{%block_item}}', 'price_old', $this->float());
        $this->addColumn('{{%block_item}}', 'currency', $this->string(10));
        $this->addColumn('{{%block_item}}', 'unit', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%block_item}}', 'upload_path');
        $this->dropColumn('{{%block_item}}', 'watermark_path');
        $this->dropColumn('{{%block_item}}', 'web_path');
    }
}
