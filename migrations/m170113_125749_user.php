<?php

use yii\db\Migration;

class m170113_125749_user extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m170113_125749_user cannot be reverted.\n";

        return false;
    }*/

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string(25)->notNull(),
            'surname' => $this->string(35)->notNull(),
            'email' => $this->string(50)->unique()->notNull(),
            'password' => $this->string(256)->unique()->notNull(),
            'role' => $this->string(30)->notNull(),
            'created' => $this->date(),
        ], $tableOptions);
        }

    public function down()
    {
        $this->dropTable('user');
    }
}
