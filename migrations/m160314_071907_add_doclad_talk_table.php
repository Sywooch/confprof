<?php

use yii\db\Schema;
use app\components\Migration;

class m160314_071907_add_doclad_talk_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%doctalk}}', [
            'dtlk_id' => Schema::TYPE_PK,
            'dtlk_us_id' => Schema::TYPE_INTEGER . ' Comment \'Пользователь\'',
            'dtlk_doc_id' => Schema::TYPE_INTEGER . ' Comment \'Доклад\'',
            'dtlk_text' => Schema::TYPE_TEXT . ' Comment \'Содержание\'',
            'dtlk_created' => Schema::TYPE_DATETIME . ' Not Null Comment \'Создан\'',
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_dtlk_doc_id', '{{%doctalk}}', 'dtlk_doc_id');
        $this->createIndex('idx_dtlk_us_id', '{{%doctalk}}', 'dtlk_us_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%doctalk}}');
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
