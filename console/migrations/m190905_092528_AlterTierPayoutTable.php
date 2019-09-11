<?php

use yii\db\Migration;

class m190905_092528_AlterTierPayoutTable extends Migration
{
    public function up()
    {
        $this->update('tiers_payout', ['team' => '0-24'], ['id' => 1]);
        $this->update('tiers_payout', ['team' => '3124-1000000'], ['id' => 5]);
    }

    public function down()
    {
        echo "m190905_092528_AlterTierPayoutTable cannot be reverted.\n";

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
