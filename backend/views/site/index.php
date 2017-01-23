<?php
defined('YII_DEBUG') or define('YII_DEBUG',true );
/* @var $this yii\web\View */

$this->title = 'We Track';
?>
<div class="jumbotron">
    <table align="center">
        <tr>
            <td>
                <h1>
                    <span class="glyphicon glyphicon-map-marker""></span>
                    We Track
                </h1>
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-lg-4 col-xs-8">
        <a href="../web/resident/index?ResidentSearch[status]=1">
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

    echo '<div class="col-md-12 " style="
            "><table border="1">';
    $ok = true;
    $row = $count;
    foreach ($model as $m)
    {
        $current = new DateTime('now');
        $time = new DateTime($m->created_at);
        $diff = $current->diff($time);
        $minutes = $diff->days * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;
        if ($minutes < 2*60) $status = '<online/>';
        else {
            if ($minutes > 24*60) $status = '<offline/>';
            else $status = '<pending/>';
        }
        foreach ($m->latestLocation as $key => $value){
            $diff_time = $diff->format('%d Days %h Hours %i Minutes') . ' ago';
            $link = "../web/location-history/view?id=" . $value->id;
            $link_resident = "../web/resident/view?id=" . $m->id;
            echo '<tr><td class="profile"id="'. $value->id . '"onclick="handleClick('. $value->id . ')" data-latitude="'.$value->latitude.'" data-longitude="'.
                $value->longitude. '" value="haha">
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
                echo '<td rowspan="6" id="replaceCell">
                        <iframe width="760" height="'.($row*84) .'" frameborder="0" style="margin: 0 auto;border:0;"
                        src='.$place .' allowfullscreen=""></iframe>
                       </td>';
                $ok = false;
            }
            echo '</tr>';
            break;
        }

    }

    echo'</table></div>'
    ?>

</div>
<script>
    function handleClick(id) {
        var long = $("#"+id).data("longitude");
        var lat = $("#"+id).data("latitude");

        $("#replaceCell").html('<iframe width="760" height="' + <?php echo ($count*84) ?> +'" frameborder="0" style="margin: 0 auto;border:0;" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA13kujZA51OzrcdJOyOngtPG13xxKsA1U&amp;q='+lat+','+long+'&amp;zoom=18" allowfullscreen=""></iframe>')
//        alert(long);
    }
</script>
