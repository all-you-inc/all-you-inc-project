<?php

use yii\db\Migration;

class m190930_165911_alterUserProfileImageTable extends Migration
{
    public function up()
    {
        $this->addColumn('user_profile_image', 'is_locked', 'int(1) default 0');
    }

    public function down()
    {
        echo "m190930_165911_alterShopProductTable cannot be reverted.\n";

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
