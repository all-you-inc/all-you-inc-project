<?php

use yii\db\Migration;

class m190829_123741_AddCurrencyTable extends Migration {

    public function up() {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'title' => ' varchar(11) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
    }

    public function down() {
        $this->dropTable('currency');
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
