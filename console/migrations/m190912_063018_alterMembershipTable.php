<?php

use yii\db\Migration;

class m190912_063018_alterMembershipTable extends Migration
{
    public function up()
    {
        $this->addColumn('membership', 'description1', 'text null after description');
//        $this->
    }

    public function down()
    {
        echo "m190912_063018_alterMembershipTable cannot be reverted.\n";

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
