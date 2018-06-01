<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Beacon */

$this->title = "Beacon [" . str_pad($model->id, 3, '0', STR_PAD_LEFT) . "]";
$this->params['breadcrumbs'][] = ['label' => 'Beacons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beacon-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            [
                'label' => "Resident Name",
                'attribute' => 'resident_id',
                'format' => 'html',
                'value' => Html::a($model->resident->fullname, ['/resident/view', 'id' => $model->resident->id])

            ],
//            "resident.fullname",
//            'resident_id',
            'uuid:ntext',
            'major',
            'minor',

//            'status',
            [
                'label' => 'Status',
                'attribute' => function ($data) {
                    return ($data->status == 1) ? "Active" : "Non-Active";

                },
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php


    ?>

    <h1><?= Html::encode("location History") ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Resident Name',
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->beacon->resident->fullname, ['/resident/view', 'id' => $data->beacon->resident->id]);
                },
            ],
            'longitude',
            'latitude',
            'created_at',
            [
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/location-history/view', 'id' => $data->id]);
                },
            ],
        ],
    ]); ?>


</div>
