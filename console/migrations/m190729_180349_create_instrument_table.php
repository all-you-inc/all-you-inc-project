<?php

use yii\db\Migration;

/**
 * Handles the creation of table `instrument`.
 */
class m190729_180349_create_instrument_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('instrument', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]
    );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('instrument');
    }
}
