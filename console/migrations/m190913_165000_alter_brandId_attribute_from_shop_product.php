<?php

use yii\db\Migration;

class m190913_165000_alter_brandId_attribute_from_shop_product extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_products', 'brand_id', $this->integer()->null());
    }

    public function down()
    {
        echo "m190913_165000_alter_brandId_attribute_from_shop_product cannot be reverted.\n";

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
