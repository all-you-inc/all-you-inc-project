<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dj_genre`.
 */
class m190729_180007_create_dj_genre_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('dj_genre', [
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
        $this->dropTable('dj_genre');
    }
}
