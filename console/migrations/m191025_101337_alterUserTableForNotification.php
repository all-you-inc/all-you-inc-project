<?php

use yii\db\Migration;

class m191025_101337_alterUserTableForNotification extends Migration {

    public function up() {
//        $this->addColumn('users', 'notification_events', 'varchar(512) NULL');
    }

    public function down() {
//        $this->dropColumn('users', 'notification_events');
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
