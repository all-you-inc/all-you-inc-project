<?php

use yii\db\Migration;

class m190802_081930_edit_phone_field_in_user_table extends Migration
{
    public function up()
    {
        $this->alterColumn('users', 'phone', $this->string()->null());
    }

    public function down()
    {
        echo "m190802_081930_edit_phone_field_in_user_table cannot be reverted.\n";

        return false;
    }
}
