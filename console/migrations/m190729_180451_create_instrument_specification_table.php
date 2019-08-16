<?php

use yii\db\Migration;

/**
 * Handles the creation of table `instrument_specification`.
 */
class m190729_180451_create_instrument_specification_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('instrument_specification', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'instrument_id' => $this->integer()->notNull()
            ]
        );

        $this->addForeignKey('instrument_id_to_instrument', 'instrument_specification', "instrument_id", 'instrument', "id");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('instrument_specification');
    }
}
