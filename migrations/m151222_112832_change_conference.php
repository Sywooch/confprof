<?php

use yii\db\Schema;
use app\components\Migration;

class m151222_112832_change_conference extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%conference}}',
            'cnf_pagetitle',
            Schema::TYPE_STRING . ' Comment \'Заголовок страниц конференции\''
        );

        $this->addColumn(
            '{{%conference}}',
            'cnf_about',
            Schema::TYPE_TEXT . ' Comment \'Текст страницы О конференции\''
        );

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%conference}}',
            'cnf_pagetitle'
        );

        $this->dropColumn(
            '{{%conference}}',
            'cnf_about'
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
