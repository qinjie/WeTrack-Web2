<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Relative */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Relatives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relative-view">

    <h1><?= Html::encode(sprintf('[%d] %s', $this->title, $model->fullname)) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fullname',
            'nric',
            'phone',
            'email:email',
            'user_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
