<?php

use yii\db\Migration;
use shop\entities\User\User;

class m190904_112129_AlterUserTable extends Migration {

    public function up() {
        $users = shop\entities\User\User::find()->all();
        if ($users) {
            foreach ($users as $user) {
                $user->referral_code = Yii::$app->SECURITY->generateRandomString();
                $user->update();
            }
        }
    }

    public function down() {
        echo "m190904_112129_AlterUserTable cannot be reverted.\n";

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
