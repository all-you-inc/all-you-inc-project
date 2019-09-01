<?php

use yii\db\Migration;

class m190829_130238_AlterPaymentTable extends Migration {

    public function up() {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'payment_method' => 'enum("online","cod") DEFAULT "online"',
            'amount' => 'decimal(11,2)',
            'currency_id' => ' int(11) NOT NULL',
            'transection_id' => ' varchar(128) NULL',
            'status' => ' int(1) DEFAULT "0"',
            'type' => 'enum("subscription","sales_order") DEFAULT NULL',
            'ref_id' => ' int(11) NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('payment_FK1', 'payment', 'user_id', 'users', 'id');
        $this->addForeignKey('payment_FK2', 'payment', 'currency_id', 'currency', 'id');
    }

    public function down() {
        $this->dropTable('payment');
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
