<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Relative */

$this->title = 'Create Relative';
$this->params['breadcrumbs'][] = ['label' => 'Relatives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relative-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
