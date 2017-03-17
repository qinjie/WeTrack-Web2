<?php
namespace api\common\models;

use common\components\Thread;
use common\components\Utils;
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

    public function beforeSave($insert)
    {
        // Get address from reverse geo-coding
        // To be remove if can do it in seperate thread in afterSave() method
//        if (!$this->address) $this->address = Utils::getAddressFromGPS($this->latitude, $this->longitude);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Update record in Location table
        if ($insert)
            $this->updateLocationTable($this);

        // TODO
        // How to create a new thread to update address so that it will not block
//        $thread1 = new Thread('updateLocationAddress');
//        $thread1->start($this);
    }

    /*
     *@param \common\models\LocationHistory $model
     */
    public function updateLocationAddress($model)
    {
        $model->address = LocationHistory::getAddress($request_url);
        $model->save();

        $loc = Location::findOne(['beacon_id' => $model->beacon_id]);
        if ($loc) {
            $loc->address = $model->address;
            $loc->save();
        }
    }

    private function updateLocationTable($new)
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