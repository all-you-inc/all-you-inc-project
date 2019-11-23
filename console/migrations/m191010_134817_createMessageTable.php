<?php

use yii\db\Migration;

class m191010_134817_createMessageTable extends Migration {

    public function up() {
        $this->createTable('chat_message', [
            'id' => $this->primaryKey(),
            'thread_id' => ' int(11) NOT NULL',
            'user_id' => ' int(11) NOT NULL',
            'body' => ' text',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' int(11) NOT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' int(11) NOT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('chat_message_FK1', 'chat_message', 'created_by', 'users', 'id');
        $this->addForeignKey('chat_message_FK2', 'chat_message', 'modified_by', 'users', 'id');
        $this->addForeignKey('chat_message_FK3', 'chat_message', 'thread_id', 'chat_thread', 'id');
        $this->addForeignKey('chat_message_FK4', 'chat_message', 'user_id', 'users', 'id');
    }

    public function down() {
        $this->dropTable('chat_message');
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
