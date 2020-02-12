<?php

use yii\db\Migration;
use yii\db\Schema;

class m190310_193342_add_table_mailing extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mailings}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255) COMMENT "заголовок письма"',
            'name_form' => Schema::TYPE_STRING . '(255) COMMENT "форма рассылки"',
            'mails' => Schema::TYPE_STRING . '(255) COMMENT "список e-mail для рассылки через звпятую"'
        ], $tableOptions);

        $this->insert('{{%mailings}}',[
            'title' => 'Запрос с сайта',
            'name_form' => 'request',
            'mails' => 'fenixq@mail.ru',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%mailings}}');
    }
}
