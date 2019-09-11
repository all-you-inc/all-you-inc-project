<?php

use yii\db\Migration;

class m190904_181112_AddTiersPayoutTable extends Migration {

    public function up() {
        $this->createTable('tiers_payout', [
            'id' => $this->primaryKey(),
            'title' => 'varchar(128) NOT NULL',
            'key' => 'varchar(128) NOT NULL',
            'percent' => 'int(11) NOT NULL',
            'team' => 'varchar(128) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
            'UNIQUE KEY `key` (`key`)',
        ]);
        $this->insert('tiers_payout', ['title' => 'SCOUT', 'key' => 'SCOUT', 'percent' => 5, 'team' => '1-24']);
        $this->insert('tiers_payout', ['title' => 'FANATIC', 'key' => 'FANATIC', 'percent' => 15, 'team' => '25-124']);
        $this->insert('tiers_payout', ['title' => 'PRO', 'key' => 'PRO', 'percent' => 20, 'team' => '125-624']);
        $this->insert('tiers_payout', ['title' => 'MOGUL', 'key' => 'MOGUL', 'percent' => 25, 'team' => '625-3124']);
        $this->insert('tiers_payout', ['title' => 'TITAN', 'key' => 'TITAN', 'percent' => 30, 'team' => '3124-X']);
    }

    public function down() {
        $this->dropTable('tiers_payout');
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
