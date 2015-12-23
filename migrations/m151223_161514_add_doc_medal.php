<?php

use yii\db\Schema;
use app\components\Migration;

class m151223_161514_add_doc_medal extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        $this->createTable('{{%docmedal}}', [
            'mdl_id' => Schema::TYPE_PK,

            'mdl_competition' => Schema::TYPE_STRING . '(255) Not Null Comment \'Конференция / конкурс\'',
            'mdl_title' => Schema::TYPE_STRING . '(255) Not Null Comment \'Результат\'',

            'mdl_doc_id' => Schema::TYPE_INTEGER . ' Comment \'Доклад\'',

        ], $tableOptionsMyISAM);

        $this->createIndex('idx_mdl_doc_id', '{{%docmedal}}', 'mdl_doc_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%docmedal}}');
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
