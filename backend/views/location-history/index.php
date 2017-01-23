<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LocationHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Location Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'beacon_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->beacon_id, ['/beacon/view', 'id' => $data->beacon_id]);
                }
            ],

//            'locator_id',
//            'user_id',

            [
                'label' => 'Resident Name',
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->resident->fullname, ['/resident/view', 'id' => $data->user_id]);
                 },


            ],
            'longitude',
             'latitude',
             'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{view} {delete}"

            ],
        ],
    ]); ?>
</div>
