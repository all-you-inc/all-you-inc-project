<?php

use yii\db\Migration;

class m190815_104415_AddCompanyFundsTable extends Migration
{
     public function up() {
        $this->createTable('company_funds', [
            'id' => $this->primaryKey(),
            'shop_product_id' => ' int(11) NOT NULL',
            'referral_code' => ' int(11) NOT NULL',
            'amount' => ' varchar(64) DEFAULT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('company_funds_FK1', 'company_funds', 'shop_product_id', 'shop_products', 'id');
    }

    public function down() {
        $this->dropTable('company_funds');
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
