<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
preg_match_all('!\d+!', $this->title, $matches);
$error = str_split($matches[0][0]);
?>
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h3><?= nl2br(Html::encode($message)) ?></h3>
				<h1><span><?= $error[0] ?></span><span><?= $error[1] ?></span><span><?= $error[2] ?></span></h1>
			</div>
			<h2>Please contact us if you think this is a server error. Thank you.</h2>
		</div>
	</div>

