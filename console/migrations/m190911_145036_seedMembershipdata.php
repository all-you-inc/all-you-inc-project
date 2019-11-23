<?php

use yii\db\Migration;

class m190911_145036_seedMembershipdata extends Migration {

    public function up() {
        $this->update('membership', ['description' =>
            '<li>Tools to get you paid</li>
            <li>We help get you fans</li>
            <li>We help showcase you</li>
            <li>Tools to improve your talent</li>
            <li>Tools to create your product</li>
            <li>We help protect you</li>
            <li>Party & VIP benefits</li>
            <li>Connect across all talent inductries</li>
'], ['id' => 1]);
        $this->update('membership', ['description' =>
            '<li>Our promoters sell your product</li>
            <li>We help get you fans</li>
            <li>We help showcase you</li>
            <li>Tools to help sell your product</li>
            <li>Tools to enhance your product</li>
            <li>We help protect you</li>
            <li>Party & VIP benefits</li>
            <li>Connect across all talent inductries</li>
'], ['id' => 2]);
        $this->update('membership', ['description' =>
            '<li>Earn from our products & services</li>
            <li>Partner with our talents</li>
            <li>Party & VIP benefits</li>
            <li>Earn income with flexibility</li>
'], ['id' => 3]);
    }

    public function down() {
        echo "m190911_145036_seedMembershipdata cannot be reverted.\n";

        return false;
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
