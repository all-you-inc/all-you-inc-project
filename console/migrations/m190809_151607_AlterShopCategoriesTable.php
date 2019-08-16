<?php

use yii\db\Migration;

class m190809_151607_AlterShopCategoriesTable extends Migration
{
    public function up()
    {
        $this->addColumn('shop_categories', 'is_dashboard', $this->boolean()->notNull());
    }

    public function down()
    {
        echo "m190809_151607_AlterShopCategoriesTable cannot be reverted.\n";

        return false;
    }
}
