<?php

use yii\db\Migration;

class m190905_173859_DropPromoterLevelTable extends Migration
{
    public function up()
    {
        $this->dropTable('promoter_level');
    }

    public function down()
    {
        echo "m190905_173859_DropPromoterLevelTable cannot be reverted.\n";

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
