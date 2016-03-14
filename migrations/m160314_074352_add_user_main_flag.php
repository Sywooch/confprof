<?php

use yii\db\Schema;
use app\components\Migration;

class m160314_074352_add_user_main_flag extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%user}}',
            'us_mainmoderator',
            Schema::TYPE_SMALLINT . ' Comment \'Главный модератор\''
        );

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropColumn(
            '{{%user}}',
            'us_mainmoderator'
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
