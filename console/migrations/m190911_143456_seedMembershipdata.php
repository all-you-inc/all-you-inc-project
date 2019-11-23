<?php

use yii\db\Migration;

class m190911_143456_seedMembershipdata extends Migration {

    public function up() {
        $this->update('membership', ['group_id' => 1, 'is_main' => 1], ['id' => 1]);
        $this->update('membership', ['group_id' => 2, 'is_main' => 1], ['id' => 2]);
        $this->update('membership', ['group_id' => 3, 'is_main' => 1], ['id' => 3]);
        $this->update('membership', ['group_id' => 4, 'is_main' => 1], ['id' => 4]);
        $this->update('membership', ['group_id' => 5, 'is_main' => 1], ['id' => 5]);
        $this->update('membership', ['group_id' => 1, 'is_main' => 0], ['id' => 6]);
        $this->update('membership', ['group_id' => 2, 'is_main' => 0], ['id' => 7]);
    }

    public function down() {
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 1]);
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 2]);
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 3]);
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 4]);
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 5]);
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 6]);
        $this->update('membership', ['group_id' => NULL, 'is_main' => NULL], ['id' => 7]);
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
