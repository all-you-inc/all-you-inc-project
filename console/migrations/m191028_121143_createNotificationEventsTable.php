<?php

use yii\db\Migration;

class m191028_121143_createNotificationEventsTable extends Migration {

    public function up() {
        $this->createTable('notification_events', [
            'id' => $this->primaryKey(),
            'event' => ' varchar(255) NOT NULL',
            'key' => ' varchar(255) NOT NULL',
            'describtion' => 'text',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' int(11) NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' int(11) NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
            'UNIQUE KEY `email` (`key`)',
        ]);
        $this->insert('notification_events', ['event' => 'User Signup', 'key' => 'UserSignup']);
        $this->insert('notification_events', ['event' => 'User Reset Password', 'key' => 'UserResetPassword']);
        $this->insert('notification_events', ['event' => 'User Payment Subscription - Success', 'key' => 'UserPaymentSubscriptionSuccess']);
        $this->insert('notification_events', ['event' => 'User Payment Subscription - Failure', 'key' => 'UserPaymentSubscriptionFailure']);
        $this->insert('notification_events', ['event' => 'User Product Purchase', 'key' => 'UserProductPurchase']);
        $this->insert('notification_events', ['event' => 'User Product Promotion Message', 'key' => 'UserProductPromotionMessage']);
        $this->insert('notification_events', ['event' => 'User Referral Signup Comission', 'key' => 'UserReferralSignupComission']);
        $this->insert('notification_events', ['event' => 'Internal Message Recevied', 'key' => 'InternalMessageRecevied']);
        $this->insert('notification_events', ['event' => 'User Fans Subscription', 'key' => 'UserFansSubscription']);
        $this->insert('notification_events', ['event' => 'User Apply For Audition', 'key' => 'UserApplyForAudition']);
        $this->insert('notification_events', ['event' => 'User Ticket Purchasing', 'key' => 'UserTicketPurchasing']);
        $this->insert('notification_events', ['event' => 'Event Host Notify For Ticket Purchasing', 'key' => 'EventHostNotifyForTicketPurchasing']);
        return TRUE;
    }

    public function down() {
        $this->dropTable('notification_events');
        return TRUE;
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
