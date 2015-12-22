<?php

use yii\db\Schema;
use app\components\Migration;

class m151222_093212_add_conference_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%conference}}', [
            'cnf_id' => Schema::TYPE_PK,
            'cnf_title' => Schema::TYPE_STRING . '(255) Not Null Comment \'Название\'',
            'cnf_class' => Schema::TYPE_STRING . '(64) Comment \'Класс шаблона\'',
            'cnf_controller' => Schema::TYPE_STRING . '(255) Comment \'Контроллер\'',
            'cnf_description' => Schema::TYPE_TEXT . ' Comment \'Текст\'',
            'cnf_created' => Schema::TYPE_DATETIME . ' Not Null Comment \'Создан\''
        ], $tableOptionsMyISAM);

//        $this->createIndex('idx_cnf_slug', '{{%conference}}', 'cnf_slug');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%conference}}');

        // need refresh cache after change table strusture
        $this->refreshCache();

        // return false;
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
