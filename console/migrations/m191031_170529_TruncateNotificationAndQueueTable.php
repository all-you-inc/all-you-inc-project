<?php

use yii\db\Migration;

class m191031_170529_TruncateNotificationAndQueueTable extends Migration {

    public function up() {
//        $this->truncateTable('queue');
//        $this->truncateTable('notification');
    }

    public function down() {
        echo "m191031_170529_TruncateNotificationAndQueueTable cannot be reverted.\n";

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
