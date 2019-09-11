<?php

use yii\db\Migration;

class m190830_151137_AlterMembershipTable extends Migration {

    public function up() {
        $this->renameColumn('membership', 'level', 'sort');
        $this->update('membership', ['title' => 'TALENT', 'description' => 'HAS NO PRODUCT', 'price' => '1.99'], ['id' => 1]);
        $this->update('membership', ['title' => 'TALENT WITH PRODUCT', 'description' => 'HAS A PRODUCT TO SELL', 'price' => '5.99'], ['id' => 2]);
        $this->update('membership', ['title' => 'PROMOTER', 'description' => 'WANTS TO SELL EVERYTHING WE CARRY', 'price' => '3.99', 'currency_id' => 1], ['id' => 3]);
        $this->insert('membership', [
            'id' => '4',
            'title' => 'FAN',
            'sort' => '4',
            'status' => 'active',
            'description' => 'FAN OF TALENTS /T ALENTS WITH PRODUCT ; FAN MUST AT LEAST INPUT THEIR NAME CELL AND EMAIL; ADDRESS IS OPTIONAL',
            'currency_id' => 1,
            'category' => NULL
        ]);
        $this->insert('membership', [
            'id' => '5',
            'title' => 'CUSTOMER',
            'sort' => '5',
            'status' => 'active',
            'description' => 'CUSTOMER MUST INPUT NORMAL INFO AS MOST CUSTOMER BIOS',
            'currency_id' => 1,
            'category' => NULL
        ]);
    }

    function down() {
        
    }

}
