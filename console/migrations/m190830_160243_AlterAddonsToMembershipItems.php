<?php

use yii\db\Migration;

class m190830_160243_AlterAddonsToMembershipItems extends Migration {

    public function up() {
        $this->renameTable('addons', 'ms_items');
        $this->dropForeignKey('addons_FK1', 'ms_items');
        $this->addColumn('ms_items', 'membership_id', 'int(11) NOT NULL after id');
        $this->addForeignKey('ms_items_FK1', 'ms_items', 'membership_id', 'membership', 'id');
        $this->alterColumn('ms_items', 'type', 'enum("free","basic","addons","membership") DEFAULT NULL');
        $this->addColumn('ms_items', 'item_type_id', 'int(11) NOT NULL after type');
        $this->addForeignKey('ms_items_FK2', 'ms_items', 'item_type_id', 'ms_item_types', 'id');
        $this->addColumn('ms_items', 'group_id', 'int(11) NOT NULL after currency_id');
        $this->addForeignKey('ms_items_FK3', 'ms_items', 'currency_id', 'currency', 'id');
    }

    public function down() {
        $this->dropTable('ms_items');
         $this->createTable('addons', [
            'id' => $this->primaryKey(),
            'unit' => ' int(11) NULL',
            'type' => 'enum("talent","talent_with_product","product") DEFAULT NULL',
            'price' => 'decimal(11,2)',
            'currency_id' => ' int(11) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('addons_FK1', 'addons', 'currency_id', 'currency', 'id');
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
