<?php

use yii\db\Migration;

class m190912_094631_AlterUserAndCompanyFundsTable extends Migration
{
    public function up()
    {
        $this->alterColumn('company_funds', 'referral_code', 'varchar(32) NULL');
        $this->alterColumn('user_funds', 'referral_code', 'varchar(32) NULL');
    }

    public function down()
    {
        echo "m190912_094631_AlterUserAndCompanyFundsTable cannot be reverted.\n";

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
