<?php

use yii\db\Schema;
use yii\db\Migration;

class m170805_192213_create_table_request extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%request}}', [
            'reviews_id' => Schema::TYPE_PK,
            'page'       => Schema::TYPE_STRING . '(20) NOT NULL',
            'type'       => Schema::TYPE_STRING . '(20) NOT NULL',
            'reviews_child' => Schema::TYPE_BOOLEAN,
            'reviews_parent' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'level' => Schema::TYPE_INTEGER . ' NULL',
            'raiting' => Schema::TYPE_INTEGER . '(2) NULL',
            'data' => Schema::TYPE_TEXT . '(2000) NULL',
            'text' => Schema::TYPE_TEXT . '(2000) NULL',
            'date_create' => Schema::TYPE_INTEGER . '(12) NOT NULL',
            'date_update' => Schema::TYPE_INTEGER . '(12) NOT NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%request}}');
    }
}