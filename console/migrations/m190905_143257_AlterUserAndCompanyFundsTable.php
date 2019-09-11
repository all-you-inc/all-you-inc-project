<?php

use yii\db\Migration;

class m190905_143257_AlterUserAndCompanyFundsTable extends Migration {

    public function up() {
        $this->addColumn('user_funds', 'transaction_id', 'varchar(32) NOT NULL after amount');
        $this->addColumn('company_funds', 'transaction_id', 'varchar(32) NOT NULL after amount');
        $this->alterColumn('company_funds', 'type', "enum('product_sale','user_signup','balance_amount') NULL");
    }

    public function down() {
        $this->dropColumn('user_funds', 'transaction_id');
        $this->dropColumn('company_funds', 'transaction_id');
        $this->alterColumn('company_funds', 'type', "enum('product_sale','user_signup') NULL");
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
