<?php

use yii\db\Migration;

class m191012_162324_CreateMessageReadTable extends Migration {

    public function up() {
        $this->createTable('chat_message_read', [
            'id' => $this->primaryKey(),
            'message_id' => ' int(11) NOT NULL',
            'user_id' => ' int(11) NOT NULL',
            'read_at' => ' bigint(20) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' int(11) NOT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' int(11) NOT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('chat_message_read_FK1', 'chat_message_read', 'created_by', 'users', 'id');
        $this->addForeignKey('chat_message_read_FK2', 'chat_message_read', 'modified_by', 'users', 'id');
        $this->addForeignKey('chat_message_read_FK3', 'chat_message_read', 'message_id', 'chat_message', 'id');
        $this->addForeignKey('chat_message_read_FK4', 'chat_message_read', 'user_id', 'users', 'id');
    }

    public function down() {
        $this->dropTable('chat_message_read');
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
