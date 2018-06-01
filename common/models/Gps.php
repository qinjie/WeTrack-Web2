<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gps".
 *
 * @property string $id
 * @property string $latitude
 * @property string $longitude
 * @property string $address
 * @property string $created_at
 *
 * @property Location[] $locations
 * @property LocationHistory[] $locationHistories
 * @property Locator[] $locators
 */
class Gps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['latitude', 'longitude', 'address'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['created_at'], 'safe'],
            [['address'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'address' => 'Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['gps_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories()
    {
        return $this->hasMany(LocationHistory::className(), ['gps_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocators()
    {
        return $this->hasMany(Locator::className(), ['gps_id' => 'id']);
    }
}
