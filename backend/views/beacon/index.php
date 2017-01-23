<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BeaconSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Beacons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beacon-index">


    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Beacon', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Resident Name',
                'attribute' => 'resident_id',
//                'value' => 'resident.fullname',
                'format' => 'html',
                'value' => function($model){
                    return Html::a($model->resident->fullname, ['/resident/view', 'id' => $model->resident_id]);
                }
            ],

            'uuid:ntext',
            'major',
            'minor',
//             'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'status',[1 => 'Active', 0 => 'Non-Active'],['class'=>'form-control','prompt' => 'Select Status']),
                'value' => function($data){
                    $d = ($data->status == 1) ? "checked" : "";
                    $s =
                        '<div class="switch">
                        <input id="' . $data->id . '"class="cmn-toggle cmn-toggle-yes-no" onclick="handleClick(this)" type="checkbox" '. $d .'>
                        <label for="' . $data->id . '"data-on="Active" data-off="Non-Active"></label>
                     </div>'

                    ;
                    return $s;
//                    return ($data->status == 1) ? "Active" : "Non-Active";

                },

            ],
             'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<script>
    var flag = false;
    function handleClick(cb) {
        if (flag) return;
        flag = true;

        //if (cb.checked)
            //alert(cb.checked);
        var form = $(this);
        $status = (cb.checked)? 0: 1;
        $.ajax({
            url: '../beacon/save',
            type: 'post',
            data: {
                id: cb.id,
                status: $status
            },
            success: function (data) {
//                alert(data);
            },
            error: function(jqXHR, errMsg) {
                // handle error
                flag = false;
//                alert(errMsg + $status + jqXHR.status);
            }
        });
    }
</script>