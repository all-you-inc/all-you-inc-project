<?php

use yii\db\Migration;

class m191028_121811_createUserNotificationTablele extends Migration {

    public function up() {
        $this->createTable('user_notification', [
            'id' => $this->primaryKey(),
            'user_id' => 'int(11) NOT NULL',
            'notification_event_id' => 'int(11) NOT NULL',
            'email' => 'int(1) DEFAULT "0"',
            'web' => 'int(1) DEFAULT "0"',
            'mobile' => 'int(1) DEFAULT "0"',
            'created_at' => 'bigint(20) DEFAULT NULL',
            'created_by' => 'int(11) NULL',
            'modified_at' => 'bigint(20) DEFAULT NULL',
            'modified_by' => 'int(11) NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_notification_FK1', 'user_notification', 'user_id', 'users', 'id');
        $this->addForeignKey('user_notification_FK2', 'user_notification', 'notification_event_id', 'notification_events', 'id');
    }

    public function down() {
        $this->dropTable('user_notification');
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
