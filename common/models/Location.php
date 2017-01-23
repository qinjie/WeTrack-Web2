<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property integer $beacon_id
 * @property integer $locator_id
 * @property integer $user_id
 * @property double $longitude
 * @property double $latitude
 * @property string $created_at
 *
 * @property Beacon $beacon
 * @property Locator $locator
 * @property User $user
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
            [['beacon_id', 'locator_id', 'user_id'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['created_at'], 'safe'],
            [['beacon_id'], 'unique'],
            [['beacon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Beacon::className(), 'targetAttribute' => ['beacon_id' => 'id']],
            [['locator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locator::className(), 'targetAttribute' => ['locator_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
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
}
