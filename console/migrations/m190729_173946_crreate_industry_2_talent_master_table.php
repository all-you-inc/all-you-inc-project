<?php

use yii\db\Migration;

class m190729_173946_crreate_industry_2_talent_master_table extends Migration
{
    public function up()
    {
        $this->createTable('industry_2_talent_master', [
            'id' => $this->primaryKey(),
            'industry_id' => $this->integer()->notNull(),
            'talent_master_id' => $this->integer()->notNull()
            ]
        );

        $this->addForeignKey('industry_id_to_industry', 'industry_2_talent_master', "industry_id", 'industry', "id");
        $this->addForeignKey('talent_master_id_to_talent_master', 'industry_2_talent_master', "talent_master_id", 'talent_master', "id");
    }

    public function down()
    {
        echo "m190729_173946_crreate_industry_2_talent_master_table cannot be reverted.\n";
        $this->dropTable('industry_2_talent_master');
        return false;
    }
}
