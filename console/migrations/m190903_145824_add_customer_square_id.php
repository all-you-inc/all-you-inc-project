<?php

use yii\db\Migration;

class m190903_145824_add_customer_square_id extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'square_cust_id', $this->string(355));
    }

    public function down()
    {
        echo "m190903_145824_add_customer_square_id cannot be reverted.\n";

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
