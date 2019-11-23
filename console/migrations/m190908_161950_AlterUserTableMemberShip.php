<?php

use yii\db\Migration;

class m190908_161950_AlterUserTableMemberShip extends Migration {

    public function up() {
        $this->addColumn('users', 'membership_id', 'int(20) after tiers_payout_id');
    }

    public function down() {
        $this->dropColumn('users', 'membership_id');
    }

}
