<?php
namespace api\common\models;
use Yii;
class Beacon extends \common\models\Beacon
{
    public function extraFields()
    {
        $new = ['resident', 'location', 'locationHistory'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }
}