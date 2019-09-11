<?php

use yii\db\Migration;

class m190905_180050_AlterUserAndCompanyFunds extends Migration {

    public function up() {      
        $this->alterColumn('user_funds', 'amount', 'decimal(11, 2) NULL');
        $this->alterColumn('company_funds', 'amount', 'decimal(11, 2) NULL');
    }

    public function down() {
        echo "m190905_180050_AlterUserAndCompanyFunds cannot be reverted.\n";

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
