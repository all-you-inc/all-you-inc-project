<?php

use yii\db\Migration;

class m190913_095244_AlterMembershipItemsSeeds extends Migration
{
    public function up()
    {
$this->update('ms_items', ['unit' => 5], ['membership_id' => 6, 'type' => 'free', 'item_type_id'=> 1]);
    }

    public function down()
    {
        echo "m190913_095244_AlterMembershipItemsSeeds cannot be reverted.\n";

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
