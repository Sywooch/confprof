<?php

use yii\db\Schema;
use app\components\Migration;

class m151222_142626_add_doclad_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%doclad}}', [
            'doc_id' => Schema::TYPE_PK,
            'doc_us_id' => Schema::TYPE_INTEGER . ' Comment \'Пользователь\'',
            'doc_sec_id' => Schema::TYPE_INTEGER . ' Comment \'Секция\'',
            'doc_type' => Schema::TYPE_STRING . '(16) Not Null Comment \'Тип доклада\'',
            'doc_subject' => Schema::TYPE_STRING . '(255) Not Null Comment \'Название\'',
            'doc_description' => Schema::TYPE_TEXT . ' Comment \'Содержание\'',
            'doc_created' => Schema::TYPE_DATETIME . ' Not Null Comment \'Создан\'',
            'doc_lider_fam' => Schema::TYPE_STRING . '(255) Not Null Comment \'Фамилия\'',
            'doc_lider_name' => Schema::TYPE_STRING . '(255) Not Null Comment \'Имя\'',
            'doc_lider_otch' => Schema::TYPE_STRING . '(255) Not Null Comment \'Отчество\'',
            'doc_lider_email' => Schema::TYPE_STRING . '(255) Not Null Comment \'Email\'',
            'doc_lider_phone' => Schema::TYPE_STRING . '(255) Not Null Comment \'Телефон\'',
            'ekis_id' => Schema::TYPE_INTEGER . ' Not Null Comment \'Организация\'',
            'doc_lider_org' => Schema::TYPE_STRING . '(255) Not Null Comment \'Организация\'',
            'doc_lider_group' => Schema::TYPE_STRING . '(255) Not Null Comment \'Класс\'',
            'doc_lider_level' => Schema::TYPE_STRING . '(255) Not Null Comment \'Курс\'',
            'doc_lider_position' => Schema::TYPE_STRING . '(255) Not Null Comment \'Должность\'',
            'doc_lider_lesson' => Schema::TYPE_STRING . '(255) Not Null Comment \'Предмет\'',
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_doc_sec_id', '{{%doclad}}', 'doc_sec_id');
        $this->createIndex('idx_doc_type', '{{%doclad}}', 'doc_type');
        $this->createIndex('idx_doc_us_id', '{{%doclad}}', 'doc_us_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%doclad}}');
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
