<?php

use yii\db\Migration;

class m191010_130409_createThreadTable extends Migration {

    public function up() {
        $this->createTable('chat_thread', [
            'id' => $this->primaryKey(),
            'title' => ' varchar(256) NOT NULL',
            'description' => ' text',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' int(11) NOT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' int(11) NOT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('chat_thread_FK1', 'chat_thread', 'created_by', 'users', 'id');
        $this->addForeignKey('chat_thread_FK2', 'chat_thread', 'modified_by', 'users', 'id');
    }

    public function down() {
        $this->dropTable('chat_thread');
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
