<?php

use yii\db\Migration;

class m191001_132032_AlterShopProductTable extends Migration
{
    public function up()
    {
        $this->addColumn('shop_products', 'is_locked', 'int(1) default 0');
    }

    public function down()
    {
        echo "m191001_132032_AlterShopProductTable cannot be reverted.\n";

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
