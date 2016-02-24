<?php

use yii\db\Schema;
use app\components\Migration;

class m160224_085658_add_user_comment extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%user}}',
            'us_description',
            Schema::TYPE_TEXT . ' Comment \'Описание\''
        );

        $this->addColumn(
            '{{%user}}',
            'us_name',
            Schema::TYPE_STRING . '(128) Comment \'ФИО\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%user}}',
            'us_description'
        );

        $this->dropColumn(
            '{{%user}}',
            'us_name'
        );

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
