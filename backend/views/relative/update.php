<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Relative */

$this->title = 'Update Relative: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Relatives', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="relative-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
