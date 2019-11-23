<?php

use yii\db\Migration;

class m190919_145055_alterMsItemsSeeds extends Migration
{
    public function up()
    {
        $this->insert('ms_items', ['membership_id' => 6, 'unit' => 1, 'type' => 'free', 'item_type_id' => 3, 'currency_id' => 1, 'group_id' => 8]);
    }

    public function down()
    {
        echo "m190919_145055_alterMsItemsSeeds cannot be reverted.\n";

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
