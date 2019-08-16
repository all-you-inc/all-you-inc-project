<?php

use yii\db\Migration;

class m190808_114803_CreateUserMembershipTable extends Migration {

    public function up() {
        $this->createTable('user_membership', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'membership_id' => ' int(11) NOT NULL',
            'status' => 'enum("active","in-active") DEFAULT "in-active"',
            'expiry_date' => ' bigint(20) DEFAULT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_membership_FK1', 'user_membership', 'user_id', 'users', 'id');
        $this->addForeignKey('user_membership_FK2', 'user_membership', 'membership_id', 'membership', 'id');
    }

    public function down() {
        $this->dropTable('user_membership');
    }
}
