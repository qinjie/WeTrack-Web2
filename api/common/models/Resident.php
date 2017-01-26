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
            'created_at',
            'reported_at',
            'remark',
            'image_path' => function(){
                if ($this->hide_photo) return "";
                return $this->image_path;
            },
            'thumbnail_path' => function(){
                if ($this->hide_photo) return "";
                return $this->thumbnail_path;
            }

        ];

//        $fields = parent::fields();
        return $fields;
    }
}