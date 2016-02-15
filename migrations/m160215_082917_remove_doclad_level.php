<?php

use yii\db\Schema;
use app\components\Migration;

class m160215_082917_remove_doclad_level extends Migration
{
    public function up()
    {
        $sSql = 'Update {{%doclad}} Set doc_lider_group = TRIM(CONCAT(doc_lider_group, \' \', doc_lider_level)) Where LENGTH(doc_lider_level) > 0';

        Yii::$app->db
            ->createCommand($sSql)
            ->execute();

        $this->dropColumn(
            '{{%doclad}}',
            'doc_lider_level'
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->addColumn(
            '{{%doclad}}',
            'doc_lider_level',
            Schema::TYPE_STRING . '(255) Not Null Comment \'Курс\''
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
