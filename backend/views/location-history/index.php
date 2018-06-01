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
    <?php
    $data = [];
    $users = \common\models\User::find()->all();
    foreach ($users as $value => $item){
//            echo $item->id . " -- " . $item->username . "\n";
        switch ($item->role){
            case 2: $data[$item->id] = "Raspberry " . $item->id;
                break;
            case 5: $data[$item->id] = "Anonymous " . $item->id;
                break;
            default:
                $data[$item->id] = $item->username;
        }
    }
    $residents = [];
    $beacons = \common\models\Beacon::find()->all();
    foreach ($beacons as $value => $item){
//                echo $item->id . " -- " . $item->resident->fullname . "\n";
        $residents[$item->id] = $item->resident->fullname;
    }
    //        var_dump($users);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'beacon_id',
                'filter' => Html::activeDropDownList($searchModel, 'beacon_id', \yii\helpers\ArrayHelper::map(\common\models\Beacon::find()->asArray()->all(), 'id', 'id')
                    ,['class'=>'form-control','prompt' => 'Select Beacon Id']),
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->beacon_id, ['/beacon/view', 'id' => $data->beacon_id]);
                }
            ],

//            'locator_id',
            [
                'attribute' => 'user_id',
                'filter' => Html::activeDropDownList($searchModel, 'user_id',$data,['class'=>'form-control','prompt' => "Select Detector's Name"]),
                'label' => 'Detector Name',
                'value' => function($data){
                    switch ($data->user->role) {
                        case 2: return "Raspberry " . $data->user_id;
                        case 5: return "Anonymous " . $data->user_id;
                        default: return $data->user->username;
                    }
                }
            ],

            [
                'label' => 'Resident Name',
                'attribute' => 'beacon_id',
                'filter' => Html::activeDropDownList($searchModel, 'beacon_id', $residents,['class'=>'form-control','prompt' => "Select Resident's Name"]),
//                'filter' => Html::activeDropDownList($searchModel, 'beacon.resident_id', \yii\helpers\ArrayHelper::map(\common\models\Resident::find()->asArray()->all(), 'id', 'fullname')
//                    ,['class'=>'form-control','prompt' => 'Select Status']),
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->beacon->resident->fullname, ['/resident/view', 'id' => $data->beacon->resident->id]);
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
