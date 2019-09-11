<?php

use yii\db\Migration;

class m190904_183620_AlterUserTable extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'tiers_payout_id', 'int(11) NULL');
    }

    public function down()
    {
        echo "m190904_183620_AlterUserTable cannot be reverted.\n";

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
