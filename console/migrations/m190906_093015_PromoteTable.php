<?php

use yii\db\Migration;

class m190906_093015_PromoteTable extends Migration {

    public function up() {
        $this->createTable('promote', [
            'id' => $this->primaryKey(),
            'user_id' => 'int(11) NOT NULL',
            'ref_id' => 'int(11) NOT NULL',
            'type' => "enum('product') NOT NULL",
            'referral_code' => 'varchar(32) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"'
        ]);
        $this->addForeignKey('promote_FK1', 'promote', 'user_id', 'users', 'id');
    }

    public function down() {
        $this->dropTable('promote');
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
