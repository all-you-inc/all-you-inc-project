<?php

use yii\db\Migration;
use common\services\UserPaymentService;

class m190830_104207_add_Previous_user_subscription_process extends Migration
{
    public function up()
    {
        $users = (new \yii\db\Query())
        ->select("*")
        ->from('users')
        ->all();

        if($users != null)
        {
            for($i = 0 ; $i < count($users) ; $i++)
            {
                $user = $users[$i];
                UserPaymentService::createSubscription('membership',1,$user['id']);
            }
        }

    }

    public function down()
    {
        echo "m190830_104207_add_Previous_user_subscription_process cannot be reverted.\n";

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
