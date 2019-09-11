<?php

use yii\db\Migration;

class m190906_192654_add_default_attribute_in_squareinfo extends Migration
{
    public function up()
    {
        $this->addColumn('users_square_info', 'status', $this->integer(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m190906_192654_add_default_attribute_in_squareinfo cannot be reverted.\n";

        return false;
    }
}
