<?php

use yii\db\Schema;
use app\components\Migration;

class m151223_091441_add_person_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%person}}', [
            'prs_id' => Schema::TYPE_PK,

            'prs_active' => Schema::TYPE_SMALLINT . ' Default 0 Comment \'Активен\'',
            'prs_type' => Schema::TYPE_INTEGER . ' Not Null Comment \'Тип участника\'',

            'prs_fam' => Schema::TYPE_STRING . '(255) Not Null Comment \'Фамилия\'',
            'prs_name' => Schema::TYPE_STRING . '(255) Not Null Comment \'Имя\'',
            'prs_otch' => Schema::TYPE_STRING . '(255) Not Null Comment \'Отчество\'',
            'prs_email' => Schema::TYPE_STRING . '(128) Not Null Comment \'Email\'',

            'prs_phone' => Schema::TYPE_STRING . '(24) Comment \'Телефон\'',

            'prs_sec_id' => Schema::TYPE_INTEGER . ' Comment \'Секция\'',
            'prs_doc_id' => Schema::TYPE_INTEGER . ' Comment \'Доклад\'',

            'ekis_id' => Schema::TYPE_INTEGER . ' Comment \'Организация\'',
            'prs_org' => Schema::TYPE_STRING . '(255) Comment \'Организация\'',

            'prs_group' => Schema::TYPE_STRING . '(64) Comment \'Класс\'',
            'prs_level' => Schema::TYPE_STRING . '(64) Comment \'Курс\'',
            'prs_position' => Schema::TYPE_STRING . '(64) Comment \'Должность\'',
            'prs_lesson' => Schema::TYPE_STRING . '(64) Comment \'Предмет\'',
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_prs_sec_id', '{{%person}}', 'prs_sec_id');
        $this->createIndex('idx_prs_type', '{{%person}}', 'prs_type');
        $this->createIndex('idx_prs_doc_id', '{{%person}}', 'prs_doc_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%person}}');
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
