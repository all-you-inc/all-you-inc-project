<?php

use yii\db\Migration;

class m190730_153243_insert_data_into_instrument extends Migration
{
    public function up()
    {
        $this->batchInsert("instrument", ["name"] ,[
                ["DRUMMER"],
                ["GUITAR PLAYER"],
                ["KEYBOARD PLAYER"],
                ["STRING PLAYER (OTHER)"],
                ["WIND INSTRUMENTS"],
            ]
        ); 
    }

    public function down()
    {
        echo "m190730_153243_insert_data_into_instrument cannot be reverted.\n";

        return false;
    }
}
