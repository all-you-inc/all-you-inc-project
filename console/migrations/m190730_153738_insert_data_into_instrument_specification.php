<?php

use yii\db\Migration;

class m190730_153738_insert_data_into_instrument_specification extends Migration
{
    public function up()
    {

        // GUITAR PLAYER
        $row = (new \yii\db\Query())
        ->select("*")
        ->from('instrument')
        ->where(['name' => 'GUITAR PLAYER'])
        ->one();

        $instrument_id = $row['id'];

        if ($instrument_id == "") {
            throw new yii\base\Exception("Could not find 'GUITAR PLAYER' in instrument table");
        }

        $this->batchInsert("instrument_specification", ["name","instrument_id"] ,[
            ["ACOUSTIC",$instrument_id],
            ["ELECTRIC",$instrument_id],
            ["SEMI- ACOUSTIC",$instrument_id],
            ["DOUBLE BASS",$instrument_id],
            ["WIND INSTRUMENTS",$instrument_id],
            ["STEEL",$instrument_id],
            ["RESONATOR",$instrument_id],
            ["ARCHTOP",$instrument_id],
            ["TWELVE STRING",$instrument_id],
        ]
    
    );
        // KEYBOARD PLAYER
        $row = (new \yii\db\Query())
        ->select("*")
        ->from('instrument')
        ->where(['name' => 'KEYBOARD PLAYER'])
        ->one();

        $instrument_id = $row['id'];

        if ($instrument_id == "") {
            throw new yii\base\Exception("Could not find 'KEYBOARD PLAYER' in instrument table");
        }

        $this->batchInsert("instrument_specification", ["name","instrument_id"] ,[
            ["PIANO",$instrument_id],
            ["ORGAN",$instrument_id],
            ["ELECTRIC",$instrument_id],
        ]
    );

        // STRING PLAYER (OTHER)
        $row = (new \yii\db\Query())
        ->select("*")
        ->from('instrument')
        ->where(['name' => 'STRING PLAYER (OTHER)'])
        ->one();

        $instrument_id = $row['id'];

        if ($instrument_id == "") {
            throw new yii\base\Exception("Could not find 'STRING PLAYER (OTHER)' in instrument table");
        }

        $this->batchInsert("instrument_specification", ["name","instrument_id"] ,[
            ["VIOLIN",$instrument_id],
            ["VIOLA",$instrument_id],
            ["CELLO",$instrument_id],
            ["MANDOLIN",$instrument_id],
            ["UKULELE",$instrument_id],
            ["HARP",$instrument_id],
        ]
    );

    // WIND INSTRUMENTS
    $row = (new \yii\db\Query())
    ->select("*")
    ->from('instrument')
    ->where(['name' => 'WIND INSTRUMENTS'])
    ->one();

    $instrument_id = $row['id'];

    if ($instrument_id == "") {
        throw new yii\base\Exception("Could not find 'WIND INSTRUMENTS' in instrument table");
    }

    $this->batchInsert("instrument_specification", ["name","instrument_id"] ,[
        ["FLUTE",$instrument_id],
        ["SAXOPHONE",$instrument_id],
        ["CLARINET",$instrument_id],
        ["TRUMPET",$instrument_id],
        ["BARITONE",$instrument_id],
        ["TROMBONE",$instrument_id],
        ["TUBA",$instrument_id],
        ["EUPHONIUMS",$instrument_id],
        ["RECORDERS",$instrument_id],
        ["BASSOONS",$instrument_id],
        ["HARMONICA",$instrument_id],
    ]
);

    }

    public function down()
    {
        echo "m190730_153738_insert_data_into_instrument_specification cannot be reverted.\n";

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
