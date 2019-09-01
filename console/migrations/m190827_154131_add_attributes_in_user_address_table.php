<?php

use yii\db\Migration;

class m190827_154131_add_attributes_in_user_address_table extends Migration
{
    public function up()
    {
        
        $this->addColumn('user_address', 'first_name'   , $this->string()->null());
        $this->addColumn('user_address', 'last_name'    , $this->string()->null());
        $this->addColumn('user_address', 'phone_number' , $this->string()->null());

    }

    public function down()
    {
        echo "m190827_154131_add_attributes_in_user_address_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
