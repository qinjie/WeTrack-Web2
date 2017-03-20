<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "locator".
 *
 * @property integer $id
 * @property string $location_name
 * @property string $location_subname
 * @property string $serial_number
 * @property double $longitude
 * @property double $latitude
 * @property string $created_at
 *
 * @property Location[] $locations
 * @property LocationHistory[] $locationHistories
 */
class Locator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location_name', 'location_subname', 'serial_number', 'longitude', 'latitude'], 'required'],
            [['location_name', 'location_subname', 'serial_number'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_name' => 'Location Name',
            'location_subname' => 'Location Subname',
            'serial_number' => 'Serial Number',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['locator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories()
    {
        return $this->hasMany(LocationHistory::className(), ['locator_id' => 'id']);
    }
}
