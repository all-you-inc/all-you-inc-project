<?php
//dd($notification->source->body);
//$this->layout = false;
//echo 'Originator name => Hello..';
use yii\helpers\Html;
// dd($notification->originator);
// echo Yii::t('OrderPlace.views_notifications_orderPlace', "%someUser% did something cool.", [
//     '%someUser%' => '<strong>' . Html::encode($notification->originator->id) . '</strong>'
// ]);
// dd(' in msg view..');
echo $originator->name.' sent you a message.';
//$source->body
 echo '<div>"'.Html::encode($source->body).'"</div>';
