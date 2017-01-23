<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Resident */

$this->title = 'Create Resident';
$this->params['breadcrumbs'][] = ['label' => 'Residents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
