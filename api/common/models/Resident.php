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
        $fields = parent::fields();
        return $fields;
    }
}