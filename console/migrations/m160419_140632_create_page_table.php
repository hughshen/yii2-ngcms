<?php

use yii\db\Migration;

class m160419_140632_create_page_table extends Migration
{
    public function up()
    {
        $tableOPtions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'excerpt' => $this->string(),
            'content' => $this->text(),
            
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%page}}');
    }
}
