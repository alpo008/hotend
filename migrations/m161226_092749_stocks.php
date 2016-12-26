<?php

use yii\db\Migration;

class m161226_092749_stocks extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m161226_092749_stocks cannot be reverted.\n";

        return false;
    }
*/

    // Use safeUp/safeDown to run migration code within a transaction
    
    public function safeUp()
    {
        $this->createTable('stocks',[
            'id' => $this->primaryKey()->notNull()->unique(),
            'placename' => $this->string(32)->notNull()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('stocks');
    }
}
