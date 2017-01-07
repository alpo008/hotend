<?php

use yii\db\Migration;

class m161226_110323_orders extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m161226_110323_orders cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'materials_id' => $this->integer(8)->notNull(),
            'qty' => $this->decimal(7, 3)->notNull()->defaultValue(0),
            'order_date' => $this->date()->notNull(),
            'status' => $this->integer(0)->defaultValue(0),
            'person' => $this->string(64),
            'docref' => $this->string(128),
            'updated' => $this->date()->defaultValue(NULL),
        ], $tableOptions);
        $this->addForeignKey('fk_orders_materials', 'orders', 'materials_id', 'materials', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('orders');
    }
}
