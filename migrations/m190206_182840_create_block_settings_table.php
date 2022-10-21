<?php

use yii\db\Migration;

/**
 * Class m190206_182840_1
 */
class m190206_182840_create_block_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%block_settings}}', [
            'id' => $this->primaryKey(),
            'block_id'  => $this->integer()->notNull(),
            'upload_path' => $this->string(),
            'photo_crop_width' => $this->integer(),
            'photo_crop_height' => $this->integer()->notNull()->defaultValue(0),
            'photo_crop_type' => $this->string(10)->notNull()->defaultValue('widen'),
            'photo_preview_crop_width' => $this->integer(),
            'photo_preview_crop_height' => $this->integer()->notNull()->defaultValue(0),
            'photo_preview_crop_type' => $this->string(10)->notNull()->defaultValue('widen'),
        ], $tableOptions);

        $this->createIndex('block_translate_block_id', '{{%block_settings}}', ['block_id'], true);

//        // demo
//        $this->insert('{{%block_settings}}', [
//            'id' => 1,
//            'block_id' => 1,
//            'upload_path' => 'blocks',
//            'photo_crop_width' => 900,
//            'photo_crop_height' => 0,
//            'photo_crop_type' => 'widen',           // 'fit'
//            'photo_preview_crop_width' => 900,
//            'photo_preview_crop_height' => 0,
//            'photo_preview_crop_type' => 'widen',   // 'fit'
//        ]);
    }

//        'savePath' => 'blocks',
//        'dir' => '@webroot/upload/blocks/',
//        'urlDir' => '/blocks',
//        //        'defaultCrop' => [900, 700],
//        'defaultCrop' => [900, 0, 'widen'],
//        'crop' => [
//        //            [850, 0, 'nw', 'widen'],
//        //            [850, 480, 'in', 'fit'],
//        ]

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%block_settings}}');
    }
}
