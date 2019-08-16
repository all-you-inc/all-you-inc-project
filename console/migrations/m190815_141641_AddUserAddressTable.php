<?php

use yii\db\Migration;

class m190815_141641_AddUserAddressTable extends Migration {

    public function up() {
        $this->createTable('user_address', [
            'id' => $this->primaryKey(),
            'user_id' => 'int(11) NOT NULL',
            'country_id' => 'int(11) NOT NULL',
            'state' => 'varchar(256) DEFAULT NULL',
            'city' => 'varchar(256) DEFAULT NULL',
            'area' => 'varchar(256) DEFAULT NULL',
            'postal_code' => 'varchar(256) DEFAULT NULL',
            'address' => 'varchar(512) DEFAULT NULL',
            'default' => 'int(1) DEFAULT "0"',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"'
        ]);
        $this->addForeignKey('user_address_FK1', 'user_address', 'user_id', 'users', 'id');
        $this->addForeignKey('user_address_FK2', 'user_address', 'country_id', 'country', 'id');
    }

    public function down() {
        echo "m190815_121641_AddUserAddressTable cannot be reverted.\n";

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
