<?php

use yii\db\Schema;
use app\components\Migration;

class m160328_143516_add_guest_limit extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%conference}}',
            'cnf_guestlimit',
            Schema::TYPE_INTEGER . ' Default 0 Comment \'Max кол-во гостей\''
        );

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%conference}}',
            'cnf_guestlimit'
        );

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
