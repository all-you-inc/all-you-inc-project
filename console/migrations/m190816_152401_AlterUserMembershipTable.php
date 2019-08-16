<?php

use yii\db\Migration;

class m190816_152401_AlterUserMembershipTable extends Migration {

    public function up() {
        $this->dropColumn('user_membership', 'expiry_date');
        $this->addColumn('user_membership', 'category', 'enum("daily","weekly","monthly","quarterly","semi-annually","annually") DEFAULT NULL');
    }

    public function down() {
        echo "m190816_152401_AlterUserMembershipTable cannot be reverted.\n";

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
