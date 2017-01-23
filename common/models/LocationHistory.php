<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "location_history".
 *
 * @property integer $id
 * @property integer $beacon_id
 * @property integer $locator_id
 * @property integer $user_id
 * @property double $longitude
 * @property double $latitude
 * @property string $created_at
 * *
 * @property Resident $resident
 */
class LocationHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['beacon_id', 'user_id', 'longitude', 'latitude'], 'required'],
            [['beacon_id', 'locator_id', 'user_id'], 'integer'],
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
            'beacon_id' => 'Beacon ID',
            'locator_id' => 'Locator ID',
            'user_id' => 'Resident ID',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'created_at' => 'Created At',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'user_id']);
    }
}
