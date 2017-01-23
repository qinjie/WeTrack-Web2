<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Resident */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Residents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resident-view">

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
            'fullname',
            'dob',
            'nric',
            'image_path:ntext',
            'thumbnail_path:ntext',
//            'status',
            [
//                'attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'attribute' => function($data){
                    return Html::encode(($data->status == 1) ?  "Missing" : "Available");
                },

            ],
            'created_at',
        ],
    ]) ?>
    <div align="center">
        <table class="tableFloorMap">
            <tr>
                <td>
                    Thumbnail
                </td>
                <td>
                    Image
                </td>
            </tr>
            <tr>
                <td>
                    <img src="../../web/<?php echo $model->thumbnail_path; ?>">
                </td>
                <td>
                    <img src="../../web/<?php echo $model->image_path; ?>">
                </td>
            </tr>
        </table>
    </div>
    <h1><?= Html::encode("Beacons") ?></h1>
    <?= GridView::widget([
        'dataProvider' => $beacons,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function($it){
                    return Html::a($it->id, ['/beacon/view', 'id' => $it->id]);
                }

            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function($data){
                    return Html::encode(($data->status == 1) ? 'Active' : 'Non-Active');
                },

            ],

        ],
    ]); ?>

    <h1><?= Html::encode("Last Location") ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'beacon_id',
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
