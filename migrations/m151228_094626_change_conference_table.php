<?php

use yii\db\Schema;
use app\components\Migration;

class m151228_094626_change_conference_table extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%conference}}',
            'cnf_shorttitle',
            Schema::TYPE_STRING . ' Comment \'Короткий заголовок\''
        );

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%conference}}',
            'cnf_shorttitle'
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
