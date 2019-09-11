<?php

use yii\db\Migration;

class m190904_194441_AddSeedMsItemsTable extends Migration {

    public function up() {
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 1, 'type' => 'basic', 'item_type_id' => 3, 'price' => '5.99', 'currency_id' => 1,'group_id'=>8]);
    }

    public function down() {
        echo "m190904_194441_AddSeedMsItemsTable cannot be reverted.\n";

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
