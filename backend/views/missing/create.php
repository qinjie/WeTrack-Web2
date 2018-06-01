<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Missing */

$this->title = 'Create Missing';
$this->params['breadcrumbs'][] = ['label' => 'Missings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="missing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
