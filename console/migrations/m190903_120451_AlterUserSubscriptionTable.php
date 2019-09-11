<?php

use yii\db\Migration;

class m190903_120451_AlterUserSubscriptionTable extends Migration
{
    public function up()
    {
        $this->addColumn('user_subscription', 'group_id', 'int(11) NULL after ref_id');
    }

    public function down()
    {
        echo "m190903_120451_AlterUserSubscriptionTable cannot be reverted.\n";

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
