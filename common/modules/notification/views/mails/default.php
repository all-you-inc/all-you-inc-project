<?php $this->beginContent('@notification/views/layouts/mail.php', $_params_); ?>
<?= $html; ?>
<br />
<br />
<?=
\common\widgets\mails\MailButtonList::widget([
    'buttons' => [
        common\widgets\mails\MailButton::widget(['url' => $url, 'text' => Yii::t('ContentModule.notifications_mails', 'View Online')])
    ]
])
?>
<?php $this->endContent(); ?>