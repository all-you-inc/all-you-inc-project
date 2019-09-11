<?php

use yii\db\Migration;

class m190903_163017_AlterSeedMsItemsTable extends Migration {

    public function up() {
        $this->update('ms_items', ['membership_id' => 6], ['type' => 'free','item_type_id'=>1]);
        $this->update('ms_items', ['membership_id' => 6], ['type' => 'free','item_type_id'=>2]);
        $this->update('ms_items', ['membership_id' => 7], ['type' => 'free','item_type_id'=>10]);
    }

    public function down() {
        echo "m190903_163017_AlterSeedMsItemsTable cannot be reverted.\n";

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
