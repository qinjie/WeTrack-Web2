<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Caregiver */

$this->title = 'Create relation';
//$this->params['breadcrumbs'][] = ['label' => 'Caregivers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caregiver-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
