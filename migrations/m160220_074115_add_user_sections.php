<?php

use yii\db\Schema;
use app\components\Migration;

class m160220_074115_add_user_sections extends Migration
{

    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';

        $this->createTable('{{%usersection}}', [
            'usec_id' => Schema::TYPE_PK,
            'usec_user_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'usec_section_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_usec_user_id', '{{%usersection}}', 'usec_user_id');
        $this->createIndex('idx_usec_section_id', '{{%usersection}}', 'usec_section_id');
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%usersection}}');
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
