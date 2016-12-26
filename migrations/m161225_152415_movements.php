<?php

use yii\db\Migration;

class m161225_152415_movements extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m161225_152415_movements cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction

    public function safeUp()
    {
        $this->createTable('movements' ,[
            'id' => $this->primaryKey(),
            'materials_id' => $this->integer(8)->notNull(),
            'direction' => $this->integer(1)->notNull()->defaultValue(0),
            'qty' => $this->decimal(7,3)->notNull()->defaultValue(0),
            'from_to' => $this->string(64)->notNull(),
            'transaction_date' => $this->date()->notNull(),
            'stocks_id' => $this->integer(8),
            'person_in_charge' => $this->string(64)->notNull(),
            'docref' => $this->string(128)
        ]);
        $this->addForeignKey('fk_movements_materials', 'movements', 'materials_id', 'materials', 'id');
        $this->addForeignKey('fk_movements_stocks', 'movements', 'stocks_id', 'stocks', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('movements');
    }

}
