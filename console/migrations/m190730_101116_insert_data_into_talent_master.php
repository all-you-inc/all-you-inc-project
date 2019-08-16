<?php

use yii\db\Migration;

class m190730_101116_insert_data_into_talent_master extends Migration
{
    public function up()
    {
        $this->batchInsert("talent_master", ["name"] ,[
            // (Industry) BOOK WRITER
            ["BOOK WRITER / NOVELIST"],
            // BROADCASTING / TALK
            ["INTERVIEWER"],
            ["TALK SHOW HOST"],
            ["NEWS HOST"],
            ["PUBLIC SPEAKER"],
            ["EVENT HOST"],
            ["VOICE OVER SPECIALIST"],
            // (Industry) CLOTHING DESIGNER / SEAMSTRESS / TAILOR
            ["CLOTHING DESIGNER"],
            ["SEAMSTRESS (WOMENS)"],
            ["TAILOR (MENS)"],
            // (Industry) DANCE
            ["BALLET DANCER"],
            ["BALLROOM DANCER"],
            ["CLASSICAL INDIAN DANCER"],
            ["CONTEMPORARY DANCER"],
            ["PERFORMANCE DANCER"],
            ["DANCEHALL DANCER"],
            ["DISCO/ELECTRONIC DANCER"],
            ["FREESTYLE / EXPERIMENTAL DANCER"],
            ["HIP HOP / STREET DANCER"],
            ["HISTORIC DANCER"],
            ["LATIN DANCER"],
            ["MODERN DANCER"],
            ["POGO DANCER"],
            ["SWING DANCER"],
            ["TAP DANCER"],
            ["TRADITIONAL JAZZ DANCER"],
            ["WORLD DANCER"],
            ["THEATRICAL DANCER"],
            // (Industry) DESIGN
            ["SET DESIGNER (MUSIC)"], 
            ["APPAREL/CLOTHING DESIGNER"],
            ["GRAPHICS DESIGNER"],
            ["SKETCH ARTIST"],
            ["WEB DESIGNER"],
            ["SET DESIGNER (FILM)"],
            ["SET DESIGNER (THEATRICAL)"],
            // (Industry) DJ
            ["CLUB / LOUNGE"],
            ["PRIVATE PARTIES"],
            ["CONVENTIONS"],
            ["WEDDINGS"],
            ["CORPORATE EVENTS"],
            // (Industry) EVENT PROMOTOR
            ["CONCERT PROMOTER"],
            ["CORPORATE EVENT ORGANIZER"],
            ["EVENT COORDINATOR"],
            ["FASHION SHOW PROMOTER"],
            ["PARTY PROMOTER"],
            ["WEDDING PLANNER"],
            // (Industry) FILM / THEATRICAL / TV
            ["ACTOR"],
            ["ACTRESS"],
            ["EXECUTIVE PRODUCER"],
            ["EXECUTIVE PRODUCER (FINANCER)"],
            ["FILM DIRECTOR"],
            ["PRODUCTION"],
            ["CASTING AGENT/DIRECTOR"],
            ["LIGHT CREW"],
            ["CAMERA CREW"],
            ["COSTUME COORDINATOR"],
            ["SCRIPT WRITER"],
            ["FILM SET/ART DESIGNER"],
            ["SET / STAGE CREW"],
            // (Industry) FINE ARTIST (PAINTER) / SCULPTOR
            ["ILLUSTRATOR"],
            ["PAINTER"],
            ["PRINT MAKER"],
            ["SKETCH ARTIST"],
            ["SCULPTOR"],
            ["INSTALLATION/EXIBIT ART"],
            ["PERFORMANCE ART"],
            ["VIDEO ART"],
            // (Industry) HAND CRAFTED CREATOR
            // NO Elements
            // (Industry) INDY TALENT COMPANY'S / MANAGERS
            ["MUSIC LABELS OWNER / MANAGER"],
            ["FILM PRODUCTION COMPANY OWNER / MANAGER"],
            ["MODELING AGENCY OWNER / MANAGER"],
            ["BOOK PUBLISHING COMPANY OWNER / MANAGER"],
            ["DANCE STUDIOS OWNER / MANAGER"],
            ["ACTING TRAINING COMPANY OWNER / MANAGER"],
            ["TALENT AGENCY OWNER / MANAGER"],
            // (Industry) MAKEUP ARTIST / ESTHETICIAN
            ["FASHION"],
            ["BODY PAINTING"],
            ["THEATRE"],
            ["TELEVISION"],
            ["Set Artist"],
            // (Industry) MISC. PERFORMER
            ["COMEDIAN"],
            ["JUGGLER"],
            ["ACROBATICS"],
            ["MAGICIAN"],
            ["POET"],
            // (Industry) MODELING
            ["ART MODEL"],
            ["CATALOG MODEL"],
            ["FASHION MODEL"],
            ["FITNESS"],
            ["GLAMOUR MODEL"],
            ["PARTS MODEL"],
            ["PLUS-SIZE MODEL"],
            ["PRINT MODEL"],
            ["PROMOTIONAL / PROMO MODEL"],
            ["RUNWAY MODEL"],
            ["SPOKESMODEL"],
            ["TV / WEB COMMERCIAL MODEL"],
            // (Industry) MUSIC
            ["BAND"],
            ["CHOIR (RELIGIOUS SINGING GROUP)"],
            ["DIRECTOR (MUSIC VIDEO)"],
            ["EXECUTIVE MUSIC PRODUCER "],
            ["EXECUTIVE PRODUCER (FINANCER)"],
            ["LYRICIST"],
            ["MANAGER (MUSIC)"],
            ["MANAGER (MUSIC/TOUR)"],
            ["MUSIC ARRANGER"],
            ["MUSIC PRODUCER (DIGITAL STUDIO)"],
            ["MUSICIAN (SOLO INSTRUMENT)"],
            ["RAP GROUP"],
            ["RAPPER (SOLO)"],
            ["SINGER (SOLO)"],
            ["SINGING GROUP"],
            ["SONG WRITER"],
            ["SOUND CREW/STAGE HAND"],
            ["SOUND ENGINEER MUSIC"],
            ["VOCAL COACH"],
            // (Industry) PHOTOGRAPHY
            ["GENERAL PHOTOGRAPHER"],
            ["ACTION / SPORTS PHOTOGRAPHER"],
            ["ADVERTISING PHOTOGRAPHER"],
            ["ARCHITECTURE PHOTOGRAPHER"],
            ["CONCERT PHOTOGRAPHER"],
            ["EVENT PHOTOGRAPHER"],
            ["FAMILY / KID PHOTOGRAPHER"],
            ["FASHION PHOTOGRAPHER"],
            ["LANDSCAPE PHOTOGRAPHER"],
            ["MICRO PHOTOGRAPHER"],
            ["NATURE PHOTOGRAPHER"],
            ["PAPARAZZI PHOTOGRAPHER"],
            ["UNDERWATER PHOTOGRAPHER"],
            ["WEDDING PHOTOGRAPHER"],
            // (Industry) SUPPORT SERVICES
            ["AGENT"],
            ["FINANCIER / INVESTOR"],
            ["IMAGE CONSULTANT"],
            ["LAWYER"],
            ["LIGHT CREW TECHNICIAN"],
            ["MAKE UP ARTIST / ESTHETICIAN"],
            ["PERSONAL SECURITY"],
            ["PERSONAL SECURITY CORPORATE"],
            ["PERSONAL TRAINER"],
            ["PUBLIC RELATIONS AGENT"],
            ["SET / STAGE CREW TECHNICIAN"],
            ["SOUND CREW TECHNICIAN"],
            // (Industry) WRITERS (OTHER THAN BOOKS)
            ["DOCUMENTARY WRITER"],
            ["FILM / SCRIPT WRITER"],
            ["JOURNALIST"],
            ["MUSIC WRITER"],
            ["POET"],
            ["TV SHOW SCRIPT WRITER"],

            ]
        );
    }

    public function down()
    {
        echo "m190730_101116_insert_data_into_talent_master cannot be reverted.\n";

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
