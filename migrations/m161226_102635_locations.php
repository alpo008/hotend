<?php

use yii\db\Migration;

class m161226_102635_locations extends Migration
{
/*
    public function up()
    {

    }

    public function down()
    {
        echo "m161226_102635_locations cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('locations',[
            'materials_id' => $this->integer(8),
            'stocks_id' => $this->integer(8),
            'qty' => $this->decimal(7,3)->defaultValue(0)
        ]);
        $this->addForeignKey('fk_locations_materials', 'locations', 'materials_id', 'materials', 'id');
        $this->addForeignKey('fk_locations_stocks', 'locations', 'stocks_id', 'stocks', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('locations');
    }
}
