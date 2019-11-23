<?php

use yii\db\Migration;

class m191031_164608_alter_user_address_table extends Migration
{
    public function up()
    {
        $this->addColumn('user_address', 'latitude', $this->string());
        $this->addColumn('user_address', 'longitude', $this->string());
    }

    public function down()
    {
        echo "m191031_164608_alter_user_address_table cannot be reverted.\n";

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
