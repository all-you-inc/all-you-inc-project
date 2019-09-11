<?php

use yii\db\Migration;

class m190904_104859_AlterUserAndCompanyfundsTable extends Migration
{
    public function up()
    {
        $this->dropForeignKey('user_funds_FK2', 'user_funds');
        $this->dropForeignKey('company_funds_FK1', 'company_funds');
        $this->dropColumn('user_funds', 'shop_product_id');
        $this->dropColumn('company_funds', 'shop_product_id');
    }

    public function down()
    {
        echo "m190904_104859_AlterUserAndCompanyfundsTable cannot be reverted.\n";

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
