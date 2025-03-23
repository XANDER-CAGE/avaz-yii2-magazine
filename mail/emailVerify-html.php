<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>

<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h1 style="color: #333;"><?= Html::encode(Yii::$app->name) ?></h1>
        <img src="<?= Yii::$app->urlManager->createAbsoluteUrl(['/img/logo.png']) ?>" alt="Логотип" style="max-width: 150px;">
    </div>
    
    <h2 style="color: #333; margin-bottom: 20px;">Подтверждение регистрации</h2>
    
    <p>Здравствуйте, <?= Html::encode($user->getFullName()) ?>!</p>
    
    <p>Благодарим вас за регистрацию на сайте <?= Html::encode(Yii::$app->name) ?>.</p>
    
    <p>Для подтверждения адреса электронной почты и активации аккаунта, пожалуйста, перейдите по следующей ссылке:</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="<?= $verifyLink ?>" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Подтвердить регистрацию</a>
    </p>
    
    <p>Или скопируйте эту ссылку в адресную строку браузера:</p>
    
    <p style="background-color: #f5f5f5; padding: 10px; border-radius: 3px; word-wrap: break-word;">
        <?= $verifyLink ?>
    </p>
    
    <p>Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.</p>
    
    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
    
    <p style="font-size: 12px; color: #777; text-align: center;">
        Это автоматическое сообщение, пожалуйста, не отвечайте на него.<br>
        © <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?>. Все права защищены.
    </p>
</div>