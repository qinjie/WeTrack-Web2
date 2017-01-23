<?php
namespace api\common\models;

use \common\models\Location;
use Yii;

class LocationHistory extends \common\models\LocationHistory
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Update record in Location table
        if ($insert)
            $this->updateLocation($this);
    }

    private function updateLocation($new)
    {
        $model = Location::findOne(['beacon_id' => $new->beacon_id]);
        if (!$model) {
            $model = new Location();
        }
        $model->attributes = $new->attributes;
        unset($model->id);
        $model->save();
    }
}