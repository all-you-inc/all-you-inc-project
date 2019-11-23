<?php

use yii\db\Migration;

class m191010_131840_createThreadParticipantTable extends Migration {

    public function up() {
        $this->createTable('chat_thread_participant', [
            'id' => $this->primaryKey(),
            'thread_id' => ' int(11) NOT NULL',
            'user_id' => ' int(11) NOT NULL',
            'status' => 'int(1) DEFAULT "0"',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' int(11) NOT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' int(11) NOT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('chat_thread_participant_FK1', 'chat_thread_participant', 'created_by', 'users', 'id');
        $this->addForeignKey('chat_thread_participant_FK2', 'chat_thread_participant', 'modified_by', 'users', 'id');
        $this->addForeignKey('chat_thread_participant_FK3', 'chat_thread_participant', 'thread_id', 'chat_thread', 'id');
        $this->addForeignKey('chat_thread_participant_FK4', 'chat_thread_participant', 'user_id', 'users', 'id');
    }

    public function down() {
        $this->dropTable('chat_thread_participant');
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
