<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Locator */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="locator-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location_name')->textInput() ?>

    <?= $form->field($model, 'location_subname')->textInput() ?>

    <?= $form->field($model, 'serial_number')->textInput() ?>

    <?= $form->field($model, 'longitude')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php
//        echo Html::button('Button 3', [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { alert("Button 3 clicked"); })();' ]);
    ?>

    <?php ActiveForm::end(); ?>

</div>
<html>
<head>
    <title></title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBWU--PRV79dW5xZz8Dc7NJynKMChajXPQ"></script>
    <script type="text/javascript">
        var geocoder = new google.maps.Geocoder();

        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    updateMarkerAddress(responses[0].formatted_address);
                } else {
                    updateMarkerAddress('Cannot determine address at this location.');
                }
            });
        }

        function updateMarkerStatus(str) {
            document.getElementById('markerStatus').innerHTML = str;
        }

        function updateMarkerPosition(latLng) {
            document.getElementById('info').innerHTML = [
                latLng.lat(),

            latLng.lng()
            ].join(', ');

            $("#locator-longitude").val(latLng.lng());
            $("#locator-latitude").val(latLng.lat());

        }

        function updateMarkerAddress(str) {
            document.getElementById('address').innerHTML = str;
            $("#locator-location_subname").val(str);
            $("#locator-location_name").val(str);

        }

        function initialize() {
            var lat= $("#locator-latitude").val();
            var long = $("#locator-longitude").val();

            var tmp = $("#info").text();
            var latLng;

            if(tmp === "0, 0"){
                latLng = new google.maps.LatLng(1.3315869, 103.781058);
            }else{
                latLng= new google.maps.LatLng(lat, long);

            }
            var map = new google.maps.Map(document.getElementById('mapCanvas'), {
                zoom: 17,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var marker = new google.maps.Marker({
                position: latLng,
                title: 'Point A',
                map: map,
                draggable: true
            });

            // Update current position info.
            updateMarkerPosition(latLng);
            geocodePosition(latLng);

            // Add dragging event listeners.
            google.maps.event.addListener(marker, 'dragstart', function() {
                updateMarkerAddress('Dragging...');
            });

            google.maps.event.addListener(marker, 'drag', function() {
                updateMarkerStatus('Dragging...');
                updateMarkerPosition(marker.getPosition());
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                updateMarkerStatus('Drag ended');
                geocodePosition(marker.getPosition());
            });
        }

        // Onload handler to fire off the app.
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <style type="text/css">
        #mapCanvas {
            width: 500px;
            height: 400px;
            float: left;
        }
        #infoPanel {
            float: left;
            margin-left: 10px;
        }
        #infoPanel div {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<h4>Drag to location to get lat/lon and location name</h4>
<div id="mapCanvas"></div>
<div id="infoPanel">
    <b>Marker status:</b>
    <div id="markerStatus"><i>Click and drag the marker.</i></div>
    <b>Current position:</b>
    <div id="info"></div>
    <b>Closest matching address:</b>
    <div id="address"></div>

</div>


</div><!--/span-->
</div><!--/row-->

</div><!--/row-->
</div><!--/span-->
</div><!--/row-->

</div><!--/.fluid-container-->



</body>
</html>