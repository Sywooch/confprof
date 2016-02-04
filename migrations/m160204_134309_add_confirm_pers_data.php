<?php

use yii\db\Schema;
use app\components\Migration;

class m160204_134309_add_confirm_pers_data extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%doclad}}',
            'doc_agree_pers',
            Schema::TYPE_INTEGER . ' Default 0 Comment \'Согласие на обработку перс. данных\''
        );

        $this->addColumn(
            '{{%person}}',
            'prs_agree_pers',
            Schema::TYPE_INTEGER . ' Default 0 Comment \'Согласие на обработку перс. данных\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%doclad}}',
            'doc_agree_pers'
        );
        $this->dropColumn(
            '{{%person}}',
            'prs_agree_pers'
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
