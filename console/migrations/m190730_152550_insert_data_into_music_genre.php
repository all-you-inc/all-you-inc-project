<?php

use yii\db\Migration;

class m190730_152550_insert_data_into_music_genre extends Migration
{
    public function up()
    {
        $this->batchInsert("music_genre", ["name"] ,[
                ["ALTERNATIVE"], 
                ["BLUES"],
                ["CLASSICAL"],
                ["COUNTRY"],
                ["DANCE"],
                ["EASY LISTENING"],
                ["ELECTRONIC"],
                ["EUROPEAN"],
                ["FOLK"],
                ["GOSPEL"],
                ["GOSPEL CONTEMPORARY"],
                ["HIP HOP / RAP"],
                ["INDIE"],
                ["INSPIRATIONAL"],
                ["JAZZ"],
                ["LATIN"],
                ["NEW AGE"],
                ["OPERA"],
                ["POP"],
                ["R&B / SOUL"],
                ["REGGAE"],
                ["ROCK"],
                ["WORLD MUSIC"],
            ]
        );
    }

    public function down()
    {
        echo "m190730_152550_insert_data_into_music_genre cannot be reverted.\n";

        return false;
    }
}
