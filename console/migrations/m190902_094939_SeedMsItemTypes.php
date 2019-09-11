<?php

use yii\db\Migration;

class m190902_094939_SeedMsItemTypes extends Migration {

    public function up() {
        $this->insert('ms_item_types', ['title' => 'PHOTOS', 'key' => 'PHOTOS']);
        $this->insert('ms_item_types', ['title' => 'VIDEOS', 'key' => 'VIDEOS']);
        $this->insert('ms_item_types', ['title' => 'TALENT', 'key' => 'TALENT']);
        $this->insert('ms_item_types', ['title' => 'ACTIVE MAP', 'key' => 'ACTIVE_MAP']);
        $this->insert('ms_item_types', ['title' => 'REAL TIME FAN ALERT', 'key' => 'REAL_TIME_FAN_ALERT']);
        $this->insert('ms_item_types', ['title' => 'FAN_SHARE ABILITY', 'key' => 'FAN_SHARE_ABILITY']);
        $this->insert('ms_item_types', ['title' => 'TIMELINE', 'key' => 'TIMELINE']);
        $this->insert('ms_item_types', ['title' => 'PARTNERSHIP ACCESS', 'key' => 'PARTNERSHIP_ACCESS']);
        $this->insert('ms_item_types', ['title' => 'PARTY & FUN VIP PRIVILEGES', 'key' => 'PARTY_&_FUN_VIP_PRIVILEGES']);
        $this->insert('ms_item_types', ['title' => 'PRODUCTS', 'key' => 'PRODUCTS']);
        $this->insert('ms_item_types', ['title' => '2ND MEMBERSHIP', 'key' => '2ND_MEMBERSHIP']);
        $this->insert('ms_item_types', ['title' => '3RD MEMBERSHIP', 'key' => '3RD_MEMBERSHIP']);
        $this->insert('ms_item_types', ['title' => 'ALL ADDITION MEMBERSHIP', 'key' => 'ALL_ADDITION_MEMBERSHIP']);
    }

    public function down() {
        $this->truncateTable('ms_item_types');
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
