<?php
//d($user->name);
//d($source->attributes);
//d($originator->name);
//dd('here');
use yii\helpers\Html;
echo Html::encode($originator->name.' sent you a message.');
//$source->body