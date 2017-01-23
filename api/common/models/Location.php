<?php
namespace api\common\models;
use Yii;
class Location extends \common\models\Location
{
    public function extraFields()
    {
        $new = ['beacon'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }
}