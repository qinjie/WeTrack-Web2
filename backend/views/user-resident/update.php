<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserResident */
$this->title = 'Update relationship';
//$this->params['breadcrumbs'][] = ['label' => 'Relations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-resident-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
