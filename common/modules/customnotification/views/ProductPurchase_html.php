<?php
use yii\helpers\Html;

echo $originator->name . ' Purchase a product of ' . $user->name;
echo '<div>"' . Html::encode('type:' . $source->type . ', ref_id:' . $source->ref_id . ', amount:' . $source->amount . ', transactionId:' . $source->transaction_id) . '"</div>';