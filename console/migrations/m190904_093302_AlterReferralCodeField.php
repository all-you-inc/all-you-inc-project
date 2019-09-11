<?php

use yii\db\Migration;

class m190904_093302_AlterReferralCodeField extends Migration {

    public function up() {
        $this->alterColumn('company_funds', 'referral_code', 'varchar(32) NOT NULL');
        $this->alterColumn('user_funds', 'referral_code', 'varchar(32) NOT NULL');
        $this->alterColumn('user_referral', 'referral_code', 'varchar(32) NOT NULL');
    }

    public function down() {
        echo "m190904_093302_AlterReferralCodeField cannot be reverted.\n";

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
