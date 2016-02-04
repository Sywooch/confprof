<?php

use yii\db\Schema;
use app\components\Migration;

class m160204_152728_add_doclad_orginal extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%doclad}}',
            'doc_work_original',
            Schema::TYPE_SMALLINT . ' Default 0 Comment \'Предоставляемая работа соответствует требованиям оригинальности\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%doclad}}',
            'doc_work_original'
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
