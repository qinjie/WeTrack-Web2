<?php
namespace api\common\models;

use Yii;

class Resident extends \common\models\Resident
{
    public function extraFields()
    {
        $new = ['beacons', 'relatives', 'locations', 'latestLocation', 'locationHistories'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

    public function fields()
    {
        $fields = [
            'id',
            'fullname',
            'dob',
            'nric',
            'status',
            'image_path' => function(){
                if ($this->hide_photo) return "uploads/human_images/no_image.png";
                return $this->image_path;
            },
            'thumbnail_path' => function(){
                if ($this->hide_photo) return "uploads/human_images/thumbnail_no_image.png";
                return $this->thumbnail_path;
            }

        ];

//        $fields = parent::fields();
        return $fields;
    }
}