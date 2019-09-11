<?php

use yii\db\Migration;

class m190830_160133_AddMembershipItemTypesTable extends Migration {

    public function up() {
        $this->createTable('ms_item_types', [
            'id' => $this->primaryKey(),
            'title' => 'varchar(128) NOT NULL',
            'key' => 'varchar(128) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
            'UNIQUE KEY `key` (`key`)',
        ]);
    }

    public function down() {
        $this->dropTable('ms_item_types');
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
