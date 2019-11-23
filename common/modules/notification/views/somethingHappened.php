<?php

use yii\helpers\Html;

echo Yii::t('SomethingHappend.views_notifications_somethingHappened', "%someUser% did something cool.", [
    '%someUser%' => '<strong>' . Html::encode($originator->id) . '</strong>'
]);