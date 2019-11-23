<?php

use yii\db\Migration;

class m191004_142735_change_talent_id_to_null_table_user_talent extends Migration
{
    public function up()
    {
        $this->alterColumn('user_talent', 'talent_id', $this->integer());
    }

    public function down()
    {
        echo "m191004_142735_change_talent_id_to_null_table_user_talent cannot be reverted.\n";

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
