<?php

use yii\db\Migration;

class m160420_134024_create_media_table extends Migration
{
    public function up()
    {
        $tableOPtions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull(),
            
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%media}}');
    }
}
