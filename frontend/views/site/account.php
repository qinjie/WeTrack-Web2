<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\models\User */

$this->title = 'Account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        = Html::a('Change Email', ['change-email'], ['class' => 'btn btn-primary'])-->
        <?= Html::a('Change Password', ['change-password'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
        ],
    ]) ?>

</div>
