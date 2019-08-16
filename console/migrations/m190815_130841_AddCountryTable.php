<?php

use yii\db\Migration;

class m190815_130841_AddCountryTable extends Migration {

    public function up() {
        $this->createTable('country', array(
            'id' => $this->primaryKey(),
            'title' => 'varchar(256) NOT NULL',
        ));
        $this->insert('country', array("title" => "Afghanistan"));
        $this->insert('country', array("title" => "Albania"));
        $this->insert('country', array("title" => "Algeria"));
        $this->insert('country', array("title" => "Andorra"));
        $this->insert('country', array("title" => "Angola"));
        $this->insert('country', array("title" => "Antigua and Barbuda"));
        $this->insert('country', array("title" => "Argentina"));
        $this->insert('country', array("title" => "Armenia"));
        $this->insert('country', array("title" => "Aruba"));
        $this->insert('country', array("title" => "Australia"));
        $this->insert('country', array("title" => "Austria"));
        $this->insert('country', array("title" => "Azerbaijan"));
        $this->insert('country', array("title" => "Bahamas, The"));
        $this->insert('country', array("title" => "Bahrain"));
        $this->insert('country', array("title" => "Bangladesh"));
        $this->insert('country', array("title" => "Barbados"));
        $this->insert('country', array("title" => "Belarus"));
        $this->insert('country', array("title" => "Belgium"));
        $this->insert('country', array("title" => "Belize"));
        $this->insert('country', array("title" => "Benin"));
        $this->insert('country', array("title" => "Bhutan"));
        $this->insert('country', array("title" => "Bolivia"));
        $this->insert('country', array("title" => "Bosnia and Herzegovina"));
        $this->insert('country', array("title" => "Botswana"));
        $this->insert('country', array("title" => "Brazil"));
        $this->insert('country', array("title" => "Brunei "));
        $this->insert('country', array("title" => "Bulgaria"));
        $this->insert('country', array("title" => "Burkina Faso"));
        $this->insert('country', array("title" => "Burma"));
        $this->insert('country', array("title" => "Burundi"));
        $this->insert('country', array("title" => "Cambodia"));
        $this->insert('country', array("title" => "Cameroon"));
        $this->insert('country', array("title" => "Canada"));
        $this->insert('country', array("title" => "Cape Verde"));
        $this->insert('country', array("title" => "Central African Republic"));
        $this->insert('country', array("title" => "Chad"));
        $this->insert('country', array("title" => "Chile"));
        $this->insert('country', array("title" => "China"));
        $this->insert('country', array("title" => "Colombia"));
        $this->insert('country', array("title" => "Comoros"));
        $this->insert('country', array("title" => "Congo, Democratic Republic of the"));
        $this->insert('country', array("title" => "Congo, Republic of the"));
        $this->insert('country', array("title" => "Costa Rica"));
        $this->insert('country', array("title" => "Cote d'Ivoire"));
        $this->insert('country', array("title" => "Croatia"));
        $this->insert('country', array("title" => "Cuba"));
        $this->insert('country', array("title" => "Curacao"));
        $this->insert('country', array("title" => "Cyprus"));
        $this->insert('country', array("title" => "Czech Republic"));
        $this->insert('country', array("title" => "Denmark"));
        $this->insert('country', array("title" => "Djibouti"));
        $this->insert('country', array("title" => "Dominica"));
        $this->insert('country', array("title" => "Dominican Republic"));
        $this->insert('country', array("title" => "Timor-Leste"));
        $this->insert('country', array("title" => "Ecuador"));
        $this->insert('country', array("title" => "Egypt"));
        $this->insert('country', array("title" => "El Salvador"));
        $this->insert('country', array("title" => "Equatorial Guinea"));
        $this->insert('country', array("title" => "Eritrea"));
        $this->insert('country', array("title" => "Estonia"));
        $this->insert('country', array("title" => "Ethiopia"));
        $this->insert('country', array("title" => "Fiji"));
        $this->insert('country', array("title" => "Finland"));
        $this->insert('country', array("title" => "France"));
        $this->insert('country', array("title" => "Gabon"));
        $this->insert('country', array("title" => "Gambia, The"));
        $this->insert('country', array("title" => "Georgia"));
        $this->insert('country', array("title" => "Germany"));
        $this->insert('country', array("title" => "Ghana"));
        $this->insert('country', array("title" => "Greece"));
        $this->insert('country', array("title" => "Grenada"));
        $this->insert('country', array("title" => "Guatemala"));
        $this->insert('country', array("title" => "Guinea"));
        $this->insert('country', array("title" => "Guinea-Bissau"));
        $this->insert('country', array("title" => "Guyana"));
        $this->insert('country', array("title" => "Haiti"));
        $this->insert('country', array("title" => "Holy See"));
        $this->insert('country', array("title" => "Honduras"));
        $this->insert('country', array("title" => "Hong Kong"));
        $this->insert('country', array("title" => "Hungary"));
        $this->insert('country', array("title" => "Iceland"));
        $this->insert('country', array("title" => "India"));
        $this->insert('country', array("title" => "Indonesia"));
        $this->insert('country', array("title" => "Iran"));
        $this->insert('country', array("title" => "Iraq"));
        $this->insert('country', array("title" => "Ireland"));
        $this->insert('country', array("title" => "Israel"));
        $this->insert('country', array("title" => "Italy"));
        $this->insert('country', array("title" => "Jamaica"));
        $this->insert('country', array("title" => "Japan"));
        $this->insert('country', array("title" => "Jordan"));
        $this->insert('country', array("title" => "Kazakhstan"));
        $this->insert('country', array("title" => "Kenya"));
        $this->insert('country', array("title" => "Kiribati"));
        $this->insert('country', array("title" => "Korea, North"));
        $this->insert('country', array("title" => "Korea, South"));
        $this->insert('country', array("title" => "Kosovo"));
        $this->insert('country', array("title" => "Kuwait"));
        $this->insert('country', array("title" => "Kyrgyzstan"));
        $this->insert('country', array("title" => "Laos"));
        $this->insert('country', array("title" => "Latvia"));
        $this->insert('country', array("title" => "Lebanon"));
        $this->insert('country', array("title" => "Lesotho"));
        $this->insert('country', array("title" => "Liberia"));
        $this->insert('country', array("title" => "Libya"));
        $this->insert('country', array("title" => "Liechtenstein"));
        $this->insert('country', array("title" => "Lithuania"));
        $this->insert('country', array("title" => "Luxembourg"));
        $this->insert('country', array("title" => "Macau"));
        $this->insert('country', array("title" => "Macedonia"));
        $this->insert('country', array("title" => "Madagascar"));
        $this->insert('country', array("title" => "Malawi"));
        $this->insert('country', array("title" => "Malaysia"));
        $this->insert('country', array("title" => "Maldives"));
        $this->insert('country', array("title" => "Mali"));
        $this->insert('country', array("title" => "Malta"));
        $this->insert('country', array("title" => "Marshall Islands"));
        $this->insert('country', array("title" => "Mauritania"));
        $this->insert('country', array("title" => "Mauritius"));
        $this->insert('country', array("title" => "Mexico"));
        $this->insert('country', array("title" => "Micronesia"));
        $this->insert('country', array("title" => "Moldova"));
        $this->insert('country', array("title" => "Monaco"));
        $this->insert('country', array("title" => "Mongolia"));
        $this->insert('country', array("title" => "Montenegro"));
        $this->insert('country', array("title" => "Morocco"));
        $this->insert('country', array("title" => "Mozambique"));
        $this->insert('country', array("title" => "Namibia"));
        $this->insert('country', array("title" => "Nauru"));
        $this->insert('country', array("title" => "Nepal"));
        $this->insert('country', array("title" => "Netherlands"));
        $this->insert('country', array("title" => "New Zealand"));
        $this->insert('country', array("title" => "Nicaragua"));
        $this->insert('country', array("title" => "Niger"));
        $this->insert('country', array("title" => "Nigeria"));
        $this->insert('country', array("title" => "North Korea"));
        $this->insert('country', array("title" => "Norway"));
        $this->insert('country', array("title" => "Oman"));
        $this->insert('country', array("title" => "Pakistan"));
        $this->insert('country', array("title" => "Palau"));
        $this->insert('country', array("title" => "Palestinian Territories"));
        $this->insert('country', array("title" => "Panama"));
        $this->insert('country', array("title" => "Papua New Guinea"));
        $this->insert('country', array("title" => "Paraguay"));
        $this->insert('country', array("title" => "Peru"));
        $this->insert('country', array("title" => "Philippines"));
        $this->insert('country', array("title" => "Poland"));
        $this->insert('country', array("title" => "Portugal"));
        $this->insert('country', array("title" => "Qatar"));
        $this->insert('country', array("title" => "Romania"));
        $this->insert('country', array("title" => "Russia"));
        $this->insert('country', array("title" => "Rwanda"));
        $this->insert('country', array("title" => "Saint Kitts and Nevis"));
        $this->insert('country', array("title" => "Saint Lucia"));
        $this->insert('country', array("title" => "Saint Vincent and the Grenadines"));
        $this->insert('country', array("title" => "Samoa "));
        $this->insert('country', array("title" => "San Marino"));
        $this->insert('country', array("title" => "Sao Tome and Principe"));
        $this->insert('country', array("title" => "Saudi Arabia"));
        $this->insert('country', array("title" => "Senegal"));
        $this->insert('country', array("title" => "Serbia"));
        $this->insert('country', array("title" => "Seychelles"));
        $this->insert('country', array("title" => "Sierra Leone"));
        $this->insert('country', array("title" => "Singapore"));
        $this->insert('country', array("title" => "Sint Maarten"));
        $this->insert('country', array("title" => "Slovakia"));
        $this->insert('country', array("title" => "Slovenia"));
        $this->insert('country', array("title" => "Solomon Islands"));
        $this->insert('country', array("title" => "Somalia"));
        $this->insert('country', array("title" => "South Africa"));
        $this->insert('country', array("title" => "South Korea"));
        $this->insert('country', array("title" => "South Sudan"));
        $this->insert('country', array("title" => "Spain "));
        $this->insert('country', array("title" => "Sri Lanka"));
        $this->insert('country', array("title" => "Sudan"));
        $this->insert('country', array("title" => "Suriname"));
        $this->insert('country', array("title" => "Swaziland "));
        $this->insert('country', array("title" => "Sweden"));
        $this->insert('country', array("title" => "Switzerland"));
        $this->insert('country', array("title" => "Syria"));
        $this->insert('country', array("title" => "Taiwan"));
        $this->insert('country', array("title" => "Tajikistan"));
        $this->insert('country', array("title" => "Tanzania"));
        $this->insert('country', array("title" => "Thailand "));
        $this->insert('country', array("title" => "Timor-Leste"));
        $this->insert('country', array("title" => "Togo"));
        $this->insert('country', array("title" => "Tonga"));
        $this->insert('country', array("title" => "Trinidad and Tobago"));
        $this->insert('country', array("title" => "Tunisia"));
        $this->insert('country', array("title" => "Turkey"));
        $this->insert('country', array("title" => "Turkmenistan"));
        $this->insert('country', array("title" => "Tuvalu"));
        $this->insert('country', array("title" => "Uganda"));
        $this->insert('country', array("title" => "Ukraine"));
        $this->insert('country', array("title" => "United Arab Emirates"));
        $this->insert('country', array("title" => "United Kingdom"));
        $this->insert('country', array("title" => "Uruguay"));
        $this->insert('country', array("title" => "Uzbekistan"));
        $this->insert('country', array("title" => "Vanuatu"));
        $this->insert('country', array("title" => "Venezuela"));
        $this->insert('country', array("title" => "Vietnam"));
        $this->insert('country', array("title" => "Yemen"));
        $this->insert('country', array("title" => "Zambia"));
        $this->insert('country', array("title" => "Zimbabwe"));
    }

    public function down() {
        $this->dropTable('country');
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
