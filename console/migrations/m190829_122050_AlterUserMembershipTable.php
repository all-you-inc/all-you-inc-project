<?php

use yii\db\Migration;

class m190829_122050_AlterUserMembershipTable extends Migration {

    public function up() {
        $this->dropTable('user_membership');
        $this->createTable('user_subscription', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'type' => 'enum("membership","addons") DEFAULT NULL',
            'ref_id' => ' int(11) NULL',
            'status' => 'enum("active","in-active") DEFAULT "in-active"',
            'category' => 'enum("daily","weekly","monthly","quarterly","semi-annually","annually") DEFAULT "monthly"',
            'last_billing_date' => ' bigint(20) DEFAULT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_subscription_FK1', 'user_subscription', 'user_id', 'users', 'id');
    }

    public function down() {
        $this->dropTable('user_subscription');
        $this->createTable('user_membership', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'membership_id' => ' int(11) NOT NULL',
            'status' => 'enum("active","in-active") DEFAULT "in-active"',
            'category' => 'enum("daily","weekly","monthly","quarterly","semi-annually","annually") DEFAULT "monthly")',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_membership_FK1', 'user_membership', 'user_id', 'users', 'id');
        $this->addForeignKey('user_membership_FK2', 'user_membership', 'membership_id', 'membership', 'id');
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
