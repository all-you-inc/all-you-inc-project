<?php

use yii\db\Migration;
use common\models\membership\Membership;

class m190829_164738_AlterMembershipTable extends Migration {

    public function up() {
        $this->addColumn('membership', 'currency_id', 'int(11) NULL after price');
        $this->addForeignKey('membership_FK1', 'membership', 'currency_id', 'currency', 'id');
        $this->update('membership', ['currency_id' => 1], ['is_deleted' => 0]);
    }

    public function down() {
        echo "m190829_164738_AlterMembershipTable cannot be reverted.\n";

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
