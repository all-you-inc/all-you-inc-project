<?php

use yii\db\Migration;

class m190829_124838_AddAddonsTable extends Migration {

    public function up() {
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

    public function down() {
        $this->dropTable('addons');
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
