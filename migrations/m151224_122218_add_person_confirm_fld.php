<?php

use yii\db\Schema;
use app\components\Migration;

class m151224_122218_add_person_confirm_fld extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%person}}',
            'prs_confirmkey',
            Schema::TYPE_STRING . ' Comment \'Ключик проверки email\''
        );

        $this->addColumn(
            '{{%person}}',
            'prs_created',
            Schema::TYPE_DATETIME . ' Comment \'Дата регистрации\''
        );

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%person}}',
            'prs_confirmkey'
        );

        $this->dropColumn(
            '{{%person}}',
            'prs_created'
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
