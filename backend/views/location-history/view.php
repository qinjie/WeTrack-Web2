<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LocationHistory */

$this->title = "Location History [" . $model->id ."]";
$this->params['breadcrumbs'][] = ['label' => 'Location Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

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
//            'beacon_id',
            [
                'attribute' => 'beacon_id',
                'format' => 'html',
                'value' => Html::a($model->beacon_id, ['/beacon/view', 'id' => $model->beacon_id])
            ],
            'locator_id',
//            'user_id',
            [
                'label' => 'Resident Name',
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' =>
                    Html::a($model->beacon->resident->fullname, ['/resident/view', 'id' => $model->beacon->resident->id])


            ],
            [
                'label' => 'Address',
//                'value' => $address,
                'format' => 'raw',
                'value' =>
                    Html::a( $url)
//                htmlEncode


            ],
            'longitude',
            'latitude',
            'created_at',
        ],
    ]) ?>

    <?php
    echo '<iframe
        width="800"
        height="500"
        frameborder="0" style="border:0"
        src=' . $place . ' allowfullscreen>
        </iframe>'
    ?>

</div>
