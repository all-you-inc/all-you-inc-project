<?php

use yii\db\Migration;

class m190815_104356_AddUserFundsTable extends Migration
{
  public function up() {
        $this->createTable('user_funds', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'shop_product_id' => ' int(11) NOT NULL',
            'referral_code' => ' int(11) NOT NULL',
            'amount' => ' varchar(64) DEFAULT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_funds_FK1', 'user_funds', 'user_id', 'users', 'id');
        $this->addForeignKey('user_funds_FK2', 'user_funds', 'shop_product_id', 'shop_products', 'id');
    }

    public function down() {
        $this->dropTable('user_funds');
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
