<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 24/5/2015
 * Time: 6:05 PM
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-email', 'token' => $user->email_confirm_token]);
?>

Hi, <?= Html::encode($user->username) ?>!
<br>
You have registered on our website.
<br>
Please download this application on GooglePlay follow the link below
<br>
https://play.google.com/store/apps/details?id=edu.np.ece.wetrack
<br>
If you have not registered on our website, then simply delete this email.