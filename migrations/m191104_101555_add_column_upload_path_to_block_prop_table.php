<?php

use yii\db\Migration;

/**
 * Class m191104_101555_1
 */
class m191104_101555_add_column_upload_path_to_block_prop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%block_prop}}', 'upload_path', $this->text());
        $this->addColumn('{{%block_prop}}', 'watermark_path', $this->text());
        $this->addColumn('{{%block_prop}}', 'web_path', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%block_prop}}', 'upload_path');
        $this->dropColumn('{{%block_prop}}', 'watermark_path');
        $this->dropColumn('{{%block_prop}}', 'web_path');
    }
}
