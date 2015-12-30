<?php

use yii\db\Schema;
use app\components\Migration;

class m151230_110425_add_file_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';

        $this->createTable('{{%file}}', [
            'file_id' => Schema::TYPE_PK,
            'file_time' => Schema::TYPE_DATETIME,
            'file_orig_name' => Schema::TYPE_STRING . ' NOT NULL',
            'file_doc_id' => Schema::TYPE_INTEGER,
            'file_us_id' => Schema::TYPE_INTEGER,
            'file_size' => Schema::TYPE_INTEGER . ' NOT NULL',
            'file_type' => Schema::TYPE_STRING,
            'file_name' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_file_doc_id', '{{%file}}', 'file_doc_id');
        $this->createIndex('idx_file_name', '{{%file}}', 'file_name');
        $this->createIndex('idx_file_us_id', '{{%file}}', 'file_us_id');
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%file}}');
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
