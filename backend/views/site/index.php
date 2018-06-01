<?php
defined('YII_DEBUG') or define('YII_DEBUG',true );
/* @var $this yii\web\View */

$this->title = 'Elderly Track';
?>
<style>
    .wrap {
        padding: 0 0 0px !important;
    }

    .container {
        height: 100% ;
        display: table;
    }
</style>
<div class="jumbotron">
    <table align="center">
        <tr>
            <td>
                <h1>
<!--                    <span class="glyphicon glyphicon-map-marker""></span>-->
                    <img src="../web/icon.png" style="
                            margin-bottom: 30px;
                            max-width: 100px;
                            max-height: 100px;
                            border-radius: 0px;
                        ">
<!--                    <img src="../web/icon.png">-->
                    Elderly Track
                </h1>
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-lg-4 col-xs-8">
        <a href="../web/resident/show-missing">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?php echo $missing; ?></h3>
                    <p>Missing Residents </p>
                </div>
                <div class="icon inner">
                    <span class="glyphicon glyphicon-ban-circle">
                    </span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-xs-8">
        <a href="../web/beacon/index">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?php echo $beacon; ?></h3>
                    <p>Beacons</p>
                </div>
                <div class="icon inner">
                    <span class="glyphicon glyphicon-eye-open">
                    </span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-xs-8">
        <a href="../web/resident/index">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $resident; ?></h3>
                    <p>Residents</p>
                </div>
                <div class="icon inner">
                    <span class="glyphicon glyphicon-user">
                    </span>
                </div>
            </div>
        </a>
    </div>
</div>

<div style="
    font-size: 24px;
    font-weight: 500;
    padding-bottom: 10px;
    /* padding-top: 5px; */
">Lastest Locations</div>

<div class="row">


    <?php
    //    echo  '<table><div class="col-md-4 ">';
    //        foreach ($model as $m)
    //             {
    //                echo '
    //                    <tr><td id="profile">
    //
    //                        <img src="' . $m->image_path.'" width="80" height="80" style="margin: 4px 5px; float: left;" />
    //                        <h2>'.$m->fullname.'<pending/></h2>
    //
    //                        </td>
    //                    </tr>';
    //            }
    //    echo '</div>';
    //    echo '<div class="col-md-8"><iframe
    //        width="750"
    //        height="500"
    //        frameborder="0" style="border:0"
    //        src=' . "https://www.google.com/maps/embed/v1/place?key=AIzaSyA13kujZA51OzrcdJOyOngtPG13xxKsA1U&q=1.3348709,103.7764856&zoom=18" . ' allowfullscreen>
    //    </iframe></div></table>';

    echo '<div class="col-md-4 scroll" style="overflow-x: hidden; overflow-y: scroll"><table border="1">';
    $ok = true;
    $row = $count;
    foreach ($model as $m)
    {
        foreach ($m->latestLocation as $key => $value){
            $current = new DateTime('now');
            $time = new DateTime($value->created_at);
            $diff = $current->diff($time);
            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;
            if ($minutes < 2*60) $status = '<online/>';
            else {
                if ($minutes > 24*60) $status = '<offline/>';
                else $status = '<pending/>';
            }
            $diff_time = $diff->format('%d Days %h Hours %i Minutes') . ' ago';
            $link = "../web/location-history/view?id=" . $value->id;
            $link_resident = "../web/resident/view?id=" . $m->id;
            echo '<tr><td class="profile"id="'. $value->id . '"onclick="handleClick('. $value->id . ')" data-latitude="'.$value->latitude.'" data-longitude="'.
                $value->longitude. '">
                                <img src="' .  $m->image_path . '" width="80" height="80" style="margin: 4px 5px; float: left;" />
                                <a href="'. $link_resident . '"><h2>' . $m->fullname . $status.'</h2></a>
                                <h3>'. $diff_time .'</h3>
                                <latitude>'. $value->latitude.'</latitude>
                                <longitude>'.$value->longitude.'</longitude>    
                                </td>';

            if ($ok) {
                $key = "AIzaSyA13kujZA51OzrcdJOyOngtPG13xxKsA1U";
                $place = "https://www.google.com/maps/embed/v1/place?key=" .$key . htmlspecialchars ('&').  'q='
                    . $value->latitude . "," . $value->longitude . "&zoom=18";
                echo '<td rowspan="'. $row.'" >
                        
                       </td>';
                $ok = false;
            }
            echo '</tr>';
            break;
        }

    }

    echo'</table></div>';
//    echo '<div class="col-md-8" id="map"></div>
//    <script>
//        var map;
//        function initMap() {
//            map = new google.maps.Map(document.getElementById(\'map\'), {
//                center: {lat: -34.397, lng: 150.644},
//                zoom: 8
//            });
//        }
//    </script>
//    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2KyH3j6ZvwAEQZ3m1Xtl0MFCP_c2Uzuk&callback=initMap"
//            async defer></script>
//</div>';
    $key = "AIzaSyA13kujZA51OzrcdJOyOngtPG13xxKsA1U";

    $place = "https://www.google.com/maps/embed/v1/place?key=" .$key . htmlspecialchars ('&').  "q=103.5,1.34&zoom=80";
//    echo '<div class="col-md-8" id="map" ><iframe width="760" height="450" frameborder="0" style="margin: 0 auto;border:0;"
//                        src="https://maps.googleapis.com/maps/api/staticmap?center=Berkeley,CA&zoom=14&size=400x400&key=AIzaSyA2KyH3j6ZvwAEQZ3m1Xtl0MFCP_c2Uzuk" allowfullscreen=""></iframe></div>';
    echo '<div class="col-md-8" id="map" >
            <iframe width="750" height="450" frameborder="0" style="margin: 0 auto;border:0;"
            src='.$place .' allowfullscreen=""></iframe></div>';

    ?>

<script>
    function handleClick(id) {
        var long = $("#"+id).data("longitude");
        var lat = $("#"+id).data("latitude");

        $("#map").html('<iframe width="760" height="450" frameborder="0" style="margin: 0 auto;border:0;" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA13kujZA51OzrcdJOyOngtPG13xxKsA1U&amp;q='+lat+','+long+'&amp;zoom=18" allowfullscreen=""></iframe>')
//        alert(long);
    }
</script>
