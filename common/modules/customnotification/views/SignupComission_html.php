<?php
use yii\helpers\Html;

echo $originator->name . ' Sent a Signup Comission to ' . $user->name;
echo '<div>"' . Html::encode('type:' . $source->type . ', ref_id:' . $source->ref_id . ', amount:' . $source->amount . ', transactionId:' . $source->transaction_id) . '"</div>';