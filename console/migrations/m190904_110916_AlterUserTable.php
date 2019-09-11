<?php

use yii\db\Migration;

class m190904_110916_AlterUserTable extends Migration {

    public function up() {
        $this->addColumn('users', 'referral_code', 'varchar(32) NULL');
    }

    public function down() {
        $this->dropColumn('users', 'referral_code');
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
