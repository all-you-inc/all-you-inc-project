<?php

use yii\db\Migration;

class m190815_102441_AddUserReferralTable extends Migration
{
   public function up() {
        $this->createTable('user_referral', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'referral_user_id' => ' int(11) NOT NULL',
            'referral_code' => ' int(11) NOT NULL',
            'tier' => ' int(11) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_referral_FK1', 'user_referral', 'user_id', 'users', 'id');
        $this->addForeignKey('user_referral_FK2', 'user_referral', 'referral_user_id', 'users', 'id');
    }

    public function down() {
        $this->dropTable('user_referral');
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
