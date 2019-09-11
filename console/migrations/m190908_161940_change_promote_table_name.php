<?php

use yii\db\Migration;

class m190908_161940_change_promote_table_name extends Migration
{
    public function up()
    {
        $this->renameTable('promote', 'promo');
    }

    public function down()
    {
        echo "m190908_161940_change_promote_table_name cannot be reverted.\n";

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
