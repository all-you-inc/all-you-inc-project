<?php

use yii\db\Migration;

class m190802_092104_add_name_field_in_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'name', $this->string()->notNull());
    }

    public function down()
    {
        echo "m190802_092104_add_name_field_in_user_table cannot be reverted.\n";

        return false;
    }
}
