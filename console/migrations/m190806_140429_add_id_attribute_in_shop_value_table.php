<?php

use yii\db\Migration;

class m190806_140429_add_id_attribute_in_shop_value_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_values', 'id',  $this->primaryKey());
    }

    public function down()
    {
        echo "m190806_140429_add_id_attribute_in_shop_value_table cannot be reverted.\n";

        return false;
    }

}
