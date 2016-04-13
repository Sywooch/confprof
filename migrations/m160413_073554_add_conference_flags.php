<?php

use yii\db\Schema;
use app\components\Migration;

class m160413_073554_add_conference_flags extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%conference}}',
            'cnf_flags',
            Schema::TYPE_INTEGER . ' Default 0 Comment \'Состояние\''
        );

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%conference}}',
            'cnf_flags'
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
