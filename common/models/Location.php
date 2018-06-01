<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property string $id
 * @property string $beacon_id
 * @property string $resident_id
 * @property string $missing_id
 * @property string $locator_id
 * @property string $user_id
 * @property string $longitude
 * @property string $latitude
 * @property string $address
 * @property string $created_at
 *
 * @property Beacon $beacon
 * @property Locator $locator
 * @property User $user
 * @property Missing $missing
 * @property Resident $resident
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['beacon_id', 'longitude', 'latitude'], 'required'],
            [['beacon_id', 'resident_id', 'missing_id', 'locator_id', 'user_id'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['created_at'], 'safe'],
            [['address'], 'string', 'max' => 50],
            [['beacon_id'], 'unique'],
            [['beacon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Beacon::className(), 'targetAttribute' => ['beacon_id' => 'id']],
            [['locator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locator::className(), 'targetAttribute' => ['locator_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['missing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Missing::className(), 'targetAttribute' => ['missing_id' => 'id']],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
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
            'resident_id' => 'Resident ID',
            'missing_id' => 'Missing ID',
            'locator_id' => 'Locator ID',
            'user_id' => 'User ID',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'address' => 'Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacon()
    {
        return $this->hasOne(Beacon::className(), ['id' => 'beacon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocator()
    {
        return $this->hasOne(Locator::className(), ['id' => 'locator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissing()
    {
        return $this->hasOne(Missing::className(), ['id' => 'missing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'resident_id']);
    }
}
