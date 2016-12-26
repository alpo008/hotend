<?php

use yii\db\Migration;

class m161225_091919_materials extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m161225_091919_materials cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('materials', [
            'id' => $this->primaryKey(),
            'ref' => $this->integer(8)->defaultValue(NULL)->unique(),
            'name' => $this->string(128)->defaultValue(NULL),
            'qty' => $this->decimal(7,3)->defaultValue(NULL),
            'minqty' => $this->decimal(7,3)->defaultValue(1.000),
            'unit' => $this->string(3)->defaultValue(NULL),
            'type' => $this->string(8)->defaultValue(NULL),
            'gruppa' => $this->string(3)->defaultValue('000'),
        ]);
        
    }

    public function safeDown()
    {
        $this->dropTable('materials');
    }

}
