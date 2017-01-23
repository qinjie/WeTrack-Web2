<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LocationHistory */

$this->title = 'Create Location History';
$this->params['breadcrumbs'][] = ['label' => 'Location Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
