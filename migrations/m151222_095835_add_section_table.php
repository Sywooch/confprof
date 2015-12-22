<?php

use yii\db\Schema;
use app\components\Migration;

class m151222_095835_add_section_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%section}}', [
            'sec_id' => Schema::TYPE_PK,
            'sec_title' => Schema::TYPE_STRING . '(255) Not Null Comment \'Название\'',
            'sec_cnf_id' => Schema::TYPE_STRING . '(64) Comment \'Конференция\'',
            'sec_created' => Schema::TYPE_DATETIME . ' Not Null Comment \'Создан\''
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_sec_cnf_id', '{{%section}}', 'sec_cnf_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%section}}');

        // need refresh cache after change table strusture
        $this->refreshCache();
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
