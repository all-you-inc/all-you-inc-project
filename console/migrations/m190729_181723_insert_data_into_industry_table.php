<?php

use yii\db\Migration;

class m190729_181723_insert_data_into_industry_table extends Migration
{
    public function up()
    {
        $this->insert("industry", 
            [
                "name" => "BOOK WRITER",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "BROADCASTING / TALK",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "CLOTHING DESIGNER / SEAMSTRESS / TAILOR",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "DANCE",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "DESIGN",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "DJ",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "EVENT PROMOTOR",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "FILM / THEATRICAL / TV",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "FINE ARTIST (PAINTER) / SCULPTOR",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "HAND CRAFTED CREATOR",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "INDY TALENT COMPANY'S / MANAGERS",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "MAKEUP ARTIST / ESTHETICIAN",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "MISC. PERFORMER",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "MODELING",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "MUSIC",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "PHOTOGRAPHY",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "SUPPORT SERVICES",
            ]
        );
        $this->insert("industry", 
            [
                "name" => "WRITERS (OTHER THAN BOOKS)",
            ]
        );
    }

    public function down()
    {
        echo "m190729_181723_insert_data_into_industry_table cannot be reverted.\n";

        return false;
    }
}
