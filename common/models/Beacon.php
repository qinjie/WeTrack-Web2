<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "beacon".
 *
 * @property string $id
 * @property string $uuid
 * @property string $major
 * @property string $minor
 * @property integer $status
 * @property string $resident_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Resident $resident
 * @property Location $location
 * @property LocationHistory[] $locationHistories
 */
class Beacon extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_SUSPENDED = 2;

    public static function GetStatusArray()
    {
        $array = [
            Beacon::STATUS_INACTIVE => 'Inactive',
            Beacon::STATUS_ACTIVE => 'Active',
            Beacon::STATUS_SUSPENDED => 'Suspended',
        ];
        return $array;
    }

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
            [['uuid', 'major', 'minor', 'status'], 'required'],
            [['major', 'minor'], 'integer', 'min' => 0, 'max' => 65535],
            ['status', 'integer'],
            ['status', 'in', 'range' => [0, 1, 2]],
            ['resident_id', 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid'], 'string', 'length' => [30, 36]],
//            ['uuid', 'match', 'pattern' => '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{6,12}'],    // UUID Validator - not working
            [['uuid', 'major', 'minor'], 'unique', 'targetAttribute' => ['uuid', 'major', 'minor'], 'message' => 'The combination of Uuid, Major and Minor has already been taken.'],
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
            'uuid' => 'Uuid',
            'major' => 'Major',
            'minor' => 'Minor',
            'status' => 'Status',
            'resident_id' => 'Resident ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['beacon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories($limit = 5)
    {
        return $this->hasMany(LocationHistory::className(), ['beacon_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC])->limit($limit);
    }
}
