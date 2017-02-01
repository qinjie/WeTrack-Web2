<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ResidentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Residents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resident-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Resident', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fullname',
            'dob',
            'nric',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'status',[1 => 'Missing', 0 => 'Available'],['class'=>'form-control','prompt' => 'Select Status']),
                'value' => function($data){
                    $d = ($data->status == 1) ? "checked" : "";
                    $s =
                        '<div class="switch">
                        <input id="' . $data->id . '"class="cmn-toggle cmn-toggle-yes-no" onclick="handleClick(this)" type="checkbox" '. $d .'>
                        <label for="' . $data->id . '"data-on="Missing" data-off="Available"></label>
                     </div>'

                    ;
                    return $s;
//                    return ($data->status == 1) ? "Missing" : "Available";

                },
            ],

             'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remark</h4>
            </div>
            <div class="modal-body">
                <label><b>Other informations</b></label>
                <textarea id="remark" class="form-control" rows="5" placeholder="Please provide more informations like last seen date/time, last seen location, last dress,..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info" data-dismiss="modal" onclick="getRemark()">Report Missing</button>
            </div>
        </div>

    </div>
</div>

<?php


?>
<?php
$script = <<< JS

    // $(function()
    // {
    //
    // $('#myBtn').click(function ()
    //             {
    //                     $('#modal').modal('show')
    //                     .find('#modalContent')
    //                     .load($(this).attr('value'));
    //                 });
    //
    // });

JS;
$this->registerJs($script);
?>
<script>
    function getRemark(){
        var resident = $("#remark").data("resident");
        var status = $("#remark").data("status");
        var remark = $('#remark').val();
        $.ajax({
            url: '../resident/remark',
            type: 'post',
            data: {
                id: resident,
                remark: remark,
                status: status
            },
            success: function (data) {
//                alert(data);
            },
            error: function(jqXHR, errMsg) {
                // handle error
//                flag = false;
//                alert(errMsg + $status + jqXHR.status);
            }
        });
    }

    var flag = false;

    function handleClick(cb) {
        if (flag) return;
        flag = true;

        //if (cb.checked)
        //alert(cb.checked);
        var form = $(this);
        $status = (cb.checked)? 0: 1;
        if ($status == 0){
            $('#myModal #remark').attr("data-resident", cb.id);
            $('#myModal #remark').attr("data-status", $status);
            $('#myModal').modal({
                    show: true,
                    backdrop: 'static'

            }
            );

//            if (!$('#myModal').hasClass('in')) {
//                // if modal is not shown/visible then do something
//                location.reload();
//            }

        }
        else {


            $.ajax({
                url: '../resident/save',
                type: 'post',
                data: {
                    id: cb.id,
                    status: $status
                },
                success: function (data) {
//                alert(data);
                },
                error: function (jqXHR, errMsg) {
                    // handle error
                    flag = false;
//                alert(errMsg + $status + jqXHR.status);
                }
            });

        }

    }

</script>
