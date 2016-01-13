<?php

use yii\db\Schema;
use app\components\Migration;

class m160113_082943_add_doclad_format extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%doclad}}',
            'doc_format',
            Schema::TYPE_INTEGER . ' Default 0 Comment \'Формат выступления\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%doclad}}',
            'doc_format'
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
