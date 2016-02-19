<?php

use yii\db\Schema;
use app\components\Migration;

class m160219_090938_add_hight_to_person extends Migration
{
    public function up()
    {

        $this->addColumn(
            '{{%person}}',
            'prs_hischool',
            Schema::TYPE_STRING . '(255) Comment \'ВУЗ\''
        );

        $this->addColumn(
            '{{%conference}}',
            'cnf_isconshicshool',
            Schema::TYPE_SMALLINT . ' Default 0 Comment \'Вводить вуз для руководителя\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%person}}',
            'prs_hischool'
        );

        $this->dropColumn(
            '{{%conference}}',
            'cnf_isconshicshool'
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
