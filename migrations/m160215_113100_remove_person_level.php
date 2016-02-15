<?php

use yii\db\Schema;
use app\components\Migration;

class m160215_113100_remove_person_level extends Migration
{
    public function up()
    {

        $sSql = 'Update {{%person}} Set prs_group = TRIM(CONCAT(prs_group, \' \', prs_level)) Where LENGTH(prs_level) > 0';

        Yii::$app->db
            ->createCommand($sSql)
            ->execute();

        $this->dropColumn(
            '{{%person}}',
            'prs_level'
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->addColumn(
            '{{%person}}',
            'prs_level',
            Schema::TYPE_STRING . '(64) Comment \'Курс\''
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
