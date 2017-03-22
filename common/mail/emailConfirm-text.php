<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-email', 'token' => $user->email_confirm_token]);
?>
Hello <?= $user->username ?>,

You have registered on our website.

Please download this application on GooglePlay follow the link below
https://play.google.com/store/apps/details?id=edu.np.ece.wetrack

If you have not registered on our website, then simply delete this email.