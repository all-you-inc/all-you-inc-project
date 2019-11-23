<?php

use yii\db\Migration;

class m191025_105048_alterUserDevicesTableForNotification extends Migration {

    public function up() {
//        $this->addColumn('user_devices', 'notification', 'int(1) DEFAULT 0');
    }

    public function down() {
        echo "m191025_105048_alterUserDevicesTableForNotification cannot be reverted.\n";

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
