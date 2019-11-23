<?php

use yii\db\Migration;

class m191001_154912_alterUserTable extends Migration {

    public function up() {
        $this->update('users', ['country' => null]);
        $this->alterColumn('users', 'country', 'int(11) NULL');
        $this->addForeignKey('users_FK1', 'users', 'country', 'country', 'id');
    }

    public function down() {
        echo "m191001_154912_alterUserTable cannot be reverted.\n";

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
