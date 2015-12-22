<?php

use yii\db\Schema;
use app\components\Migration;

class m151222_133251_add_conference_id_to_user extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%user}}',
            'us_conference_id',
            Schema::TYPE_INTEGER . ' Comment \'Конференция регистрации\''
        );

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%user}}',
            'us_conference_id'
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
