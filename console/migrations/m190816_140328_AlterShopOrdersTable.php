<?php

use yii\db\Migration;

class m190816_140328_AlterShopOrdersTable extends Migration {

    public function up() {
        $this->alterColumn('shop_orders', 'delivery_address', 'int(11) NOT NULL');
        $this->addForeignKey('idx-shop_orders-delivery_address', 'shop_orders', 'delivery_address', 'user_address', 'id');
    }

    public function down() {
        echo "m190816_140328_AlterShopOrdersTable cannot be reverted.\n";

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
