<?php

use yii\db\Migration;

class m190902_103331_SeedMsItem extends Migration {

    public function up() {
        //Talent
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'free', 'item_type_id' => 1, 'currency_id' => 1, 'group_id' => 1]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'free', 'item_type_id' => 2, 'currency_id' => 1, 'group_id' => 1]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 2, 'type' => 'basic', 'item_type_id' => 3, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 4, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 5, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 6, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 7, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 8, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 10, 'type' => 'basic', 'item_type_id' => 1, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 3, 'type' => 'basic', 'item_type_id' => 2, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 9, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 2]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 1, 'type' => 'addons', 'item_type_id' => 3, 'price' => 0.99, 'currency_id' => 1, 'group_id' => 3]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 10, 'type' => 'addons', 'item_type_id' => 1, 'price' => 0.99, 'currency_id' => 1, 'group_id' => 4]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 3, 'type' => 'addons', 'item_type_id' => 2, 'price' => 0.99, 'currency_id' => 1, 'group_id' => 5]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 30, 'type' => 'addons', 'item_type_id' => 1, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 6]);
        $this->insert('ms_items', ['membership_id' => 1, 'unit' => 10, 'type' => 'addons', 'item_type_id' => 2, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 6]);
        
        //Talent with Product
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 5, 'type' => 'free', 'item_type_id' => 10, 'currency_id' => 1, 'group_id' => 7]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 25, 'type' => 'basic', 'item_type_id' => 10, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 4, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 5, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 6, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 7, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 8, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 20, 'type' => 'basic', 'item_type_id' => 1, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 3, 'type' => 'basic', 'item_type_id' => 2, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'basic', 'item_type_id' => 9, 'price' => 5.99, 'currency_id' => 1, 'group_id' => 8]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 10, 'type' => 'addons', 'item_type_id' => 10, 'price' => 1.99, 'currency_id' => 1, 'group_id' => 9]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 10, 'type' => 'addons', 'item_type_id' => 1, 'price' => 0.99, 'currency_id' => 1, 'group_id' => 10]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 3, 'type' => 'addons', 'item_type_id' => 2, 'price' => 0.99, 'currency_id' => 1, 'group_id' => 11]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'membership', 'item_type_id' => 11, 'price' => 4.99, 'currency_id' => 1, 'group_id' => 12]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'membership', 'item_type_id' => 12, 'price' => 3.99, 'currency_id' => 1, 'group_id' => 13]);
        $this->insert('ms_items', ['membership_id' => 2, 'unit' => 0, 'type' => 'membership', 'item_type_id' => 13, 'price' => 2.99, 'currency_id' => 1, 'group_id' => 14]);
    }

    public function down() {
        $this->truncateTable('ms_items');
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
