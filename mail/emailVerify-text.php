<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Здравствуйте, <?= $user->getFullName() ?>!

Благодарим вас за регистрацию на сайте <?= Yii::$app->name ?>.

Для подтверждения адреса электронной почты и активации аккаунта, пожалуйста, перейдите по следующей ссылке:

<?= $verifyLink ?>

Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.

--
Это автоматическое сообщение, пожалуйста, не отвечайте на него.
© <?= date('Y') ?> <?= Yii::$app->name ?>. Все права защищены.