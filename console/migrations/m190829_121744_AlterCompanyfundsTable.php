<?php

use yii\db\Migration;

class m190829_121744_AlterCompanyfundsTable extends Migration {

    public function up() {
        $this->addColumn('company_funds', 'type', 'enum("product_sale","user_signup") DEFAULT NULL after referral_code');
        $this->addColumn('company_funds', 'ref_id', 'int(11) NULL after type');
    }

    public function down() {
        $this->dropColumn('company_funds', 'type');
        $this->dropColumn('company_funds', 'ref_id');
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
