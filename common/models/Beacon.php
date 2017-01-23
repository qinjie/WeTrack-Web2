<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "beacon".
 *
 * @property integer $id
 * @property integer $resident_id
 * @property string $uuid
 * @property integer $major
 * @property integer $minor
 * @property integer $status
 * @property string $created_at
 *
 * @property Resident $resident
 * @property Location $location
 */
class Beacon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resident_id', 'major', 'minor', 'status'], 'integer'],
            [['uuid', 'major', 'minor', 'status'], 'required'],
            [['uuid'], 'string'],
            [['created_at'], 'safe'],
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
            'resident_id' => 'Resident ID',
            'uuid' => 'Uuid',
            'major' => 'Major',
            'minor' => 'Minor',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'resident_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * Return only latest location from <location> table
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['beacon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * Return all related <location_history> records. It limits number of location to 5.
     */
    public function getLocationHistory($limit = 5)
    {
        return $this->hasMany(LocationHistory::className(), ['beacon_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC])->limit($limit);
    }
}
