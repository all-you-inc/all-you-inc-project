<?php

use yii\db\Migration;

class m190829_155201_SeedCurrencyTable extends Migration {

    public function up() {
        $this->insert('currency', [
            'id' => '1',
            'title' => 'USD'
        ]);
        $this->insert('currency', [
            'id' => '2',
            'title' => '$'
        ]);
        $this->insert('currency', [
            'id' => '3',
            'title' => 'PKR'
        ]);
    }

    public function down() {
        echo "m190829_155201_SeedCurrencyTable cannot be reverted.\n";

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
