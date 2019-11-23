<?php

use yii\db\Migration;

class m190930_165911_alterUserProfileImageTable extends Migration
{
    public function up()
    {
        $this->addColumn('shop_products', 'talent_id', $this->integer()->null());
        $this->addForeignKey('FK_talentId_to_user_talent', 'shop_products', 'talent_id', 'user_talent', 'id');
    }

    public function down()
    {
        echo "m190916_230147_add_talent_id_in_shop_products cannot be reverted.\n";

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
