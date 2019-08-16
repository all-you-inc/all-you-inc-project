<?php

use yii\db\Schema;
use yii\db\Migration;

class m190729_173135_crreate_industry_table extends Migration
{
    public function up()
    {
        $this->createTable('industry', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()
            ]
        );
    }

    public function down()
    {
        echo "m190729_173135_crreate_industry_table cannot be reverted.\n";
        $this->dropTable('industry');
        return false;
    }
}
