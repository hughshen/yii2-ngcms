<?php

use yii\db\Migration;

class m160411_140021_install extends Migration
{
    public function up()
    {
        $tableOPtions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // create user table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // create slider table
        $this->createTable('{{%slider}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'caption' => $this->text(),
            'img_url' => $this->string()->notNull(),
            'link_url' => $this->string()->notNull(),
            'link_target' => $this->string(10),
            'ordering' => $this->integer()->defaultValue(0),
            
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%slider}}');
    }
}
