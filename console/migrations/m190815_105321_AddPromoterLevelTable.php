<?php

use yii\db\Migration;

class m190815_105321_AddPromoterLevelTable extends Migration {

    public function up() {
        $this->createTable('promoter_level', [
            'id' => $this->primaryKey(),
            'level' => ' varchar(64) DEFAULT NULL',
            'payout_per' => ' varchar(64) DEFAULT NULL',
            'limit' => ' varchar(64) DEFAULT NULL',
            'description' => ' text',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->insert("promoter_level", ['level' => 'SCOUT','payout_per' => '5','limit' => '0-24']);
        $this->insert("promoter_level", ['level' => 'FANATIC','payout_per' => '15','limit' => '25-124']);
        $this->insert("promoter_level", ['level' => 'PRO','payout_per' => '20','limit' => '125-624']);
        $this->insert("promoter_level", ['level' => 'MOGUL','payout_per' => '25','limit' => '625-3124']);
        $this->insert("promoter_level", ['level' => 'TITAN','payout_per' => '30','limit' => '3125-x']);
    }

    public function down() {
        $this->dropTable('promoter_level');
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
