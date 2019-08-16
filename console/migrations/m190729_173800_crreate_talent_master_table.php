<?php

use yii\db\Migration;

class m190729_173800_crreate_talent_master_table extends Migration
{
    public function up()
    {
        $this->createTable('talent_master', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]
    );
    }

    public function down()
    {
        echo "m190729_173800_crreate_talent_master_table cannot be reverted.\n";
        $this->dropTable('talent_master');
        return false;
    }

}
