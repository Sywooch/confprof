<?php

use yii\db\Schema;
use app\components\Migration;

class m151222_120545_add_user_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%user}}', [
            'us_id' => Schema::TYPE_PK,
            'us_group' => Schema::TYPE_STRING . '(16) Comment \'Группа\'',
            'us_active' => Schema::TYPE_SMALLINT . ' Default 1 Comment \'Активен\'',
            'us_email' => Schema::TYPE_STRING . '(64) Not Null Comment \'Электронная почта\'',
            'us_pass' => Schema::TYPE_STRING . ' Not Null Comment \'Пароль\'',
            'us_created' => Schema::TYPE_DATETIME . ' Not Null Comment \'Создан\'',
            'us_confirmkey' => Schema::TYPE_STRING . ' Comment \'Confirm key\'',
            'us_key' => Schema::TYPE_STRING . ' Comment \'API key\'',
        ], $tableOptionsMyISAM);
        $this->createIndex('idx_us_email', '{{%user}}', 'us_email');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
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
