<?php

use yii\db\Migration;

class m190912_073141_alterMembershipTable extends Migration {

    public function up() {
        $this->update('membership', ['description1' =>
            '<li>2 Talent Memberships</li>
<li>Share Fan Base</li>
<li>Real Time Fan Alerts</li>
<li>Premium Talent Network Access</li>
<li>Premium Online Auditions</li>
<li>Premium Event Access</li>
<li>Up To 10 Photos</li>
<li>Up To 3 Videos</li>
<li>Active Map Access</li>
<li>Timeline Access</li>
<li>Party & VIP Benefits</li>
<li>Promoter Partnership Access</li>
<li>Active Charts Access</li>
<li>Plus all Free Membership Stuff</li>
'], ['id' => 1]);
        $this->update('membership', ['description1' =>
            '<li>No more than 5 pictures</li>
<li>No videos</li>
<li>Access to Talent Network</li>
<li>Access to Online Auditions</li>
<li>Access to Online Events</li>
<li>Access to Marketing Tools</li>
<li>Access to Learning Center</li>
<li>Access to Legal Tools</li>
'], ['id' => 6]);

        $this->update('membership', ['description1' =>
            '<li>Talent Memberships</li>
<li>UP TO 25 (TWENTY FIVE) PRODUCTS PLACEMENTS</li>
<li>Up To 20 Photos</li>
<li>Up To 3 Videos</li>
<li>Real Time Fan Alerts</li>
<li>Active Map Access</li>
<li>Timeline Access</li>
<li>Party & VIP Benefits</li>
<li>Promoter Partnership Access</li>
<li>Active Charts Access</li>
<li>Plus all Free Membership Stuff</li>
'], ['id' => 2]);
        $this->update('membership', ['description1' =>
            '<li>No more than 5 Product</li>
<li>No videos</li>
<li>Access to Marketing Tools</li>
<li>Access to Learning Center</li>
<li>Access to Legal Tools</li>
'], ['id' => 7]);
    }

    public function down() {
        echo "m190912_073141_alterMembershipTable cannot be reverted.\n";

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
