<?php

use yii\db\Schema;
use app\components\Migration;

class m160315_115917_add_main_moderator extends Migration
{
    public function up()
    {
        $this->addColumn('{{%usersection}}', 'usec_section_primary', Schema::TYPE_SMALLINT . ' Comment \'Ответственный\'');
//        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
//        $this->createTable('{{%primemoderator}}', [
//            'prime_id' => Schema::TYPE_PK,
//            'prime_us_id' => Schema::TYPE_INTEGER . ' Comment \'Пользователь\'',
//            'prime_sec_id' => Schema::TYPE_INTEGER . ' Comment \'Секция\'',
//        ], $tableOptionsMyISAM);
//
//        $this->createIndex('idx_prime_us_id', '{{%primemoderator}}', 'prime_us_id');
//        $this->createIndex('idx_prime_sec_id', '{{%primemoderator}}', 'prime_sec_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
//        $this->dropTable('{{%primemoderator}}');
        $this->dropColumn('{{%usersection}}', 'usec_section_primary');
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
