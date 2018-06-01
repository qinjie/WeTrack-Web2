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
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],       // hide "(not set)" if value is null
        'attributes' => [
            'id',
            'fullname',
            'dob',
            'nric',
            'image_path:ntext',
            'thumbnail_path:ntext',
            [
//                'attribute' =>  'hide_photo',
                'label' => 'Image display',
                'format' => 'raw',
                'value' => $model->getHidePhotoLabel()
//                'attribute' => function ($data) {
//                    return Html::encode(($data->hide_photo == 1) ? "No" : "Yes");
            ],
            [
//                'attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => $model->getStatusLabel()
//                'attribute' => function ($data) {
//                    return Html::encode(($data->status == 1) ? "Missing" : "Available");
//                },
            ],
            'remark',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <div align="center">
        <img src="../../web/<?php echo $model->thumbnail_path; ?>">
    </div>

    <h1>Beacon List</h1>
    <?= GridView::widget([
        'dataProvider' => $beacons,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'uuid',
            'major',
            'minor',
            [
                'attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::encode(($data->status == 1) ? 'Active' : 'Non-Active');
                },
            ],
            [
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/beacon/view', 'id' => $data->id]);
                },
            ],
        ],
    ]); ?>

    <br>
    <h1>Caregivers</h1>
    <?= GridView::widget([
        'dataProvider' => $caregivers,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'relative.fullname',
            'relative.phone',
            'relative.email',
            'relative.user_id',
            'relation',
            [
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/relative/view', 'id' => $data->relative_id]);
                },
            ],
        ],
    ]); ?>

    <br>
    <h1>Missing Cases</h1>
    <?= GridView::widget([
        'dataProvider' => $missings,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            ['attribute' => 'created_at',
                'label' => 'Date',
                'value' => function ($data) {
                    return $data->getReportedDate();
                },
            ],
            'reported_by',
            ['attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getStatusLabel();
                },
            ],
            [
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/missing/view', 'id' => $data->id]);
                },
            ],
        ],
    ]); ?>

</div>
