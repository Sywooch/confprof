<?php

use yii\db\Schema;
use app\components\Migration;

class m160219_111552_add_section_part extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%section}}',
            'sec_doclad_type',
            Schema::TYPE_STRING . '(16) Comment \'Тип доклада\''
        );
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%section}}',
            'sec_doclad_type'
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
