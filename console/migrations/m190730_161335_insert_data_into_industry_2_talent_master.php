<?php

use yii\db\Migration;

class m190730_161335_insert_data_into_industry_2_talent_master extends Migration
{
    public function up()
    {
        // BOOK WRITER
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'BOOK WRITER'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'BOOK WRITER' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => 'BOOK WRITER / NOVELIST'])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception("Could not find 'BOOK WRITER / NOVELIST' in talent_master table");
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // BROADCASTING / TALK
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'BROADCASTING / TALK'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'BROADCASTING / TALK' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "INTERVIEWER",
            "TALK SHOW HOST",
            "NEWS HOST",
            "PUBLIC SPEAKER",
            "EVENT HOST",
            "VOICE OVER SPECIALIST",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["INTERVIEWER"],
            ["TALK SHOW HOST"],
            ["NEWS HOST"],
            ["PUBLIC SPEAKER"],
            ["EVENT HOST"],
            ["VOICE OVER SPECIALIST"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // CLOTHING DESIGNER / SEAMSTRESS / TAILOR
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'CLOTHING DESIGNER / SEAMSTRESS / TAILOR'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception('Could not find ["CLOTHING DESIGNER"],
            ["SEAMSTRESS (WOMENS)"],
            ["TAILOR (MENS)"], in industry table');
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "CLOTHING DESIGNER",
            "SEAMSTRESS (WOMENS)",
            "TAILOR (MENS)",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception("Could not find 'BOOK WRITER / NOVELIST' in talent_master table");
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // DANCE
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'DANCE'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'DANCE' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "BALLET DANCER",
            "BALLROOM DANCER",
            "CLASSICAL INDIAN DANCER",
            "CONTEMPORARY DANCER",
            "PERFORMANCE DANCER",
            "DANCEHALL DANCER",
            "DISCO/ELECTRONIC DANCER",
            "FREESTYLE / EXPERIMENTAL DANCER",
            "HIP HOP / STREET DANCER",
            "HISTORIC DANCER",
            "LATIN DANCER",
            "MODERN DANCER",
            "POGO DANCER",
            "SWING DANCER",
            "TAP DANCER",
            "TRADITIONAL JAZZ DANCER",
            "WORLD DANCER",
            "THEATRICAL DANCER",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["BALLET DANCER"],
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
            ["THEATRICAL DANCER"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }

        // DESIGN
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'DESIGN'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'DESIGN' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "SET DESIGNER (MUSIC)", 
            "APPAREL/CLOTHING DESIGNER",
            "GRAPHICS DESIGNER",
            "SKETCH ARTIST",
            "WEB DESIGNER",
            "SET DESIGNER (FILM)",
            "SET DESIGNER (THEATRICAL)",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["SET DESIGNER (MUSIC)"], 
            ["APPAREL/CLOTHING DESIGNER"],
            ["GRAPHICS DESIGNER"],
            ["SKETCH ARTIST"],
            ["WEB DESIGNER"],
            ["SET DESIGNER (FILM)"],
            ["SET DESIGNER (THEATRICAL)"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // DJ
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'DJ'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'DJ' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "CLUB / LOUNGE",
            "PRIVATE PARTIES",
            "CONVENTIONS",
            "WEDDINGS",
            "CORPORATE EVENTS",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["CLUB / LOUNGE"],
            ["PRIVATE PARTIES"],
            ["CONVENTIONS"],
            ["WEDDINGS"],
            ["CORPORATE EVENTS"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // EVENT PROMOTOR
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'EVENT PROMOTOR'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'EVENT PROMOTOR' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "CONCERT PROMOTER",
            "CORPORATE EVENT ORGANIZER",
            "EVENT COORDINATOR",
            "FASHION SHOW PROMOTER",
            "PARTY PROMOTER",
            "WEDDING PLANNER",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["CONCERT PROMOTER"],
            ["CORPORATE EVENT ORGANIZER"],
            ["EVENT COORDINATOR"],
            ["FASHION SHOW PROMOTER"],
            ["PARTY PROMOTER"],
            ["WEDDING PLANNER"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


         // FILM / THEATRICAL / TV
         $industry = (new \yii\db\Query())
         ->select("*")
         ->from('industry')
         ->where(['name' => 'FILM / THEATRICAL / TV'])
         ->one();
 
         $industry_id = $industry['id'];
 
         if ($industry_id == "") {
             throw new yii\base\Exception("Could not find 'FILM / THEATRICAL / TV' in industry table");
         }
 
         $talent_master = (new \yii\db\Query())
         ->select("*")
         ->from('talent_master')
         ->where(['name' => [
            "ACTOR",
            "ACTRESS",
            "EXECUTIVE PRODUCER",
            "EXECUTIVE PRODUCER (FINANCER)",
            "FILM DIRECTOR",
            "PRODUCTION",
            "CASTING AGENT/DIRECTOR",
            "LIGHT CREW",
            "CAMERA CREW",
            "COSTUME COORDINATOR",
            "SCRIPT WRITER",
            "FILM SET/ART DESIGNER",
            "SET / STAGE CREW",
         ]])->all();
 
         if ($talent_master == NULL) {
             throw new yii\base\Exception('Could not find  ["ACTOR"],
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
             ["SET / STAGE CREW"], in talent_master table');
         }
 
         for($i = 0 ; $i < count($talent_master) ; $i++){
             $this->insert("industry_2_talent_master", 
                 [
                     "industry_id" => $industry_id,
                     "talent_master_id" => $talent_master[$i]['id']
                 ]
             );
         }


         // FINE ARTIST (PAINTER) / SCULPTOR
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'FINE ARTIST (PAINTER) / SCULPTOR'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'FINE ARTIST (PAINTER) / SCULPTOR' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "ILLUSTRATOR",
            "PAINTER",
            "PRINT MAKER",
            "SKETCH ARTIST",
            "SCULPTOR",
            "INSTALLATION/EXIBIT ART",
            "PERFORMANCE ART",
            "VIDEO ART",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["ILLUSTRATOR"],
            ["PAINTER"],
            ["PRINT MAKER"],
            ["SKETCH ARTIST"],
            ["SCULPTOR"],
            ["INSTALLATION/EXIBIT ART"],
            ["PERFORMANCE ART"],
            ["VIDEO ART"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // INDY TALENT COMPANY'S / MANAGERS
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => "INDY TALENT COMPANY'S / MANAGERS"])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find INDY TALENT COMPANY'S / MANAGERS in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "MUSIC LABELS OWNER / MANAGER",
            "FILM PRODUCTION COMPANY OWNER / MANAGER",
            "MODELING AGENCY OWNER / MANAGER",
            "BOOK PUBLISHING COMPANY OWNER / MANAGER",
            "DANCE STUDIOS OWNER / MANAGER",
            "ACTING TRAINING COMPANY OWNER / MANAGER",
            "TALENT AGENCY OWNER / MANAGER",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find  ["MUSIC LABELS OWNER / MANAGER"],
            ["FILM PRODUCTION COMPANY OWNER / MANAGER"],
            ["MODELING AGENCY OWNER / MANAGER"],
            ["BOOK PUBLISHING COMPANY OWNER / MANAGER"],
            ["DANCE STUDIOS OWNER / MANAGER"],
            ["ACTING TRAINING COMPANY OWNER / MANAGER"],
            ["TALENT AGENCY OWNER / MANAGER"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // MAKEUP ARTIST / ESTHETICIAN
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'MAKEUP ARTIST / ESTHETICIAN'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'MAKEUP ARTIST / ESTHETICIAN' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "FASHION",
            "BODY PAINTING",
            "THEATRE",
            "TELEVISION",
            "Set Artist",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["FASHION"],
            ["BODY PAINTING"],
            ["THEATRE"],
            ["TELEVISION"],
            ["Set Artist"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // MISC. PERFORMER
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'MISC. PERFORMER'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'MISC. PERFORMER' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "COMEDIAN",
            "JUGGLER",
            "ACROBATICS",
            "MAGICIAN",
            "POET",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["COMEDIAN"],
            ["JUGGLER"],
            ["ACROBATICS"],
            ["MAGICIAN"],
            ["POET"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // MODELING
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'MODELING'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'MODELING' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "ART MODEL",
            "CATALOG MODEL",
            "FASHION MODEL",
            "FITNESS",
            "GLAMOUR MODEL",
            "PARTS MODEL",
            "PLUS-SIZE MODEL",
            "PRINT MODEL",
            "PROMOTIONAL / PROMO MODEL",
            "RUNWAY MODEL",
            "SPOKESMODEL",
            "TV / WEB COMMERCIAL MODEL",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["ART MODEL"],
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
            ["TV / WEB COMMERCIAL MODEL"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // MUSIC
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'MUSIC'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'MUSIC' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "BAND",
            "CHOIR (RELIGIOUS SINGING GROUP)",
            "DIRECTOR (MUSIC VIDEO)",
            "EXECUTIVE MUSIC PRODUCER ",
            "EXECUTIVE PRODUCER (FINANCER)",
            "LYRICIST",
            "MANAGER (MUSIC)",
            "MANAGER (MUSIC/TOUR)",
            "MUSIC ARRANGER",
            "MUSIC PRODUCER (DIGITAL STUDIO)",
            "MUSICIAN (SOLO INSTRUMENT)",
            "RAP GROUP",
            "RAPPER (SOLO)",
            "SINGER (SOLO)",
            "SINGING GROUP",
            "SONG WRITER",
            "SOUND CREW/STAGE HAND",
            "SOUND ENGINEER MUSIC",
            "VOCAL COACH",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["BAND"],
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
            ["VOCAL COACH"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // PHOTOGRAPHY
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'PHOTOGRAPHY'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'PHOTOGRAPHY' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "GENERAL PHOTOGRAPHER",
            "ACTION / SPORTS PHOTOGRAPHER",
            "ADVERTISING PHOTOGRAPHER",
            "ARCHITECTURE PHOTOGRAPHER",
            "CONCERT PHOTOGRAPHER",
            "EVENT PHOTOGRAPHER",
            "FAMILY / KID PHOTOGRAPHER",
            "FASHION PHOTOGRAPHER",
            "LANDSCAPE PHOTOGRAPHER",
            "MICRO PHOTOGRAPHER",
            "NATURE PHOTOGRAPHER",
            "PAPARAZZI PHOTOGRAPHER",
            "UNDERWATER PHOTOGRAPHER",
            "WEDDING PHOTOGRAPHER",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["GENERAL PHOTOGRAPHER"],
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
            ["WEDDING PHOTOGRAPHER"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // SUPPORT SERVICES
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'SUPPORT SERVICES'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'SUPPORT SERVICES' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "AGENT",
            "FINANCIER / INVESTOR",
            "IMAGE CONSULTANT",
            "LAWYER",
            "LIGHT CREW TECHNICIAN",
            "MAKE UP ARTIST / ESTHETICIAN",
            "PERSONAL SECURITY",
            "PERSONAL SECURITY CORPORATE",
            "PERSONAL TRAINER",
            "PUBLIC RELATIONS AGENT",
            "SET / STAGE CREW TECHNICIAN",
            "SOUND CREW TECHNICIAN",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["AGENT"],
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
            ["SOUND CREW TECHNICIAN"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }


        // WRITERS (OTHER THAN BOOKS)
        $industry = (new \yii\db\Query())
        ->select("*")
        ->from('industry')
        ->where(['name' => 'WRITERS (OTHER THAN BOOKS)'])
        ->one();

        $industry_id = $industry['id'];

        if ($industry_id == "") {
            throw new yii\base\Exception("Could not find 'WRITERS (OTHER THAN BOOKS)' in industry table");
        }

        $talent_master = (new \yii\db\Query())
        ->select("*")
        ->from('talent_master')
        ->where(['name' => [
            "DOCUMENTARY WRITER",
            "FILM / SCRIPT WRITER",
            "JOURNALIST",
            "MUSIC WRITER",
            "POET",
            "TV SHOW SCRIPT WRITER",
        ]])->all();

        if ($talent_master == NULL) {
            throw new yii\base\Exception('Could not find ["DOCUMENTARY WRITER"],
            ["FILM / SCRIPT WRITER"],
            ["JOURNALIST"],
            ["MUSIC WRITER"],
            ["POET"],
            ["TV SHOW SCRIPT WRITER"], in talent_master table');
        }

        for($i = 0 ; $i < count($talent_master) ; $i++){
            $this->insert("industry_2_talent_master", 
                [
                    "industry_id" => $industry_id,
                    "talent_master_id" => $talent_master[$i]['id']
                ]
            );
        }
    }




    public function down()
    {
        echo "m190730_161335_insert_data_into_industry_2_talent_master cannot be reverted.\n";

        return false;
    }

}
