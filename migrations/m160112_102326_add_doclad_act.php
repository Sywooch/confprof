<?php

use yii\db\Schema;
use app\components\Migration;

class m160112_102326_add_doclad_act extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%doclad}}',
            'doc_state',
            Schema::TYPE_INTEGER . ' Default 0 Comment \'Статус\''
        );

        $this->addColumn(
            '{{%doclad}}',
            'doc_comment',
            Schema::TYPE_TEXT . ' Comment \'Комментарий\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%doclad}}',
            'doc_state'
        );

        $this->dropColumn(
            '{{%doclad}}',
            'doc_comment'
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
