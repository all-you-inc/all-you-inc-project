<?php

use yii\db\Migration;

class m190903_111803_AlterMembershipTable extends Migration {

    public function up() {
        $this->insert('membership', [
            'id' => '6',
            'title' => 'FREE-TALENT',
            'sort' => '6',
            'status' => 'active',
            'description' => 'ALL NEW SIGNUPS GET SPECIAL 60 DAYS OF FREE UPGRADE; MUST INSERT CREDIT/DEBIT CARD ON SIGNUP',
            'currency_id' => 1,
            'category' => NULL
        ]);
        $this->insert('membership', [
            'id' => '7',
            'title' => 'FREE-TALENT WITH PRODUCT',
            'sort' => '7',
            'status' => 'active',
            'description' => 'ALL NEW SIGNUPS GET SPECIAL 60 DAYS OF FREE UPGRADE; MUST INSERT CREDIT/DEBIT CARD ON SIGNUP',
            'currency_id' => 1,
            'category' => NULL
        ]);
    }

    public function down() {
        echo "m190903_111803_AlterMembershipTable cannot be reverted.\n";

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
