<?php

use yii\db\Migration;

class m190908_161960_AlterMemberShipTable extends Migration {

    public function up() {
        $this->addColumn('membership', 'group_id', 'int(20) after modified_by');
        $this->addColumn('membership', 'is_main', 'int(2) after group_id');
    }

    public function down() {
        $this->dropColumn('membership', 'group_id');
        $this->dropColumn('membership', 'is_main');
    }

}
