<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resident".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $dob
 * @property string $nric
 * @property string $image_path
 * @property string $thumbnail_path
 * @property integer $status
 * @property string $created_at
 *
 * @property Beacon[] $beacons
 * @property UserResident[] $userResidents
 */
class Resident extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;

    public static function tableName()
    {
        return 'resident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'dob', 'nric', 'status'], 'required'],
            [['dob', 'created_at'], 'safe'],
            [['image_path', 'thumbnail_path', 'remark'], 'string'],
            [['status'], 'integer'],
            [['fullname', 'nric'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Fullname',
            'dob' => 'Dob',
            'nric' => 'Nric',
            'image_path' => 'Image Path',
            'thumbnail_path' => 'Thumbnail Path',
            'status' => 'Status',
            'created_at' => 'Created At',
            'remark' => 'Remark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacons()
    {
        return $this->hasMany(Beacon::className(), ['resident_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserResidents()
    {
        return $this->hasMany(UserResident::className(), ['resident_id' => 'id']);
    }

    /**
     * @return \api\common\models\User
     * Get list of User
     */
    public function getRelatives()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via('userResidents');
    }

    /**
     * @return \api\common\models\Location
     * Return latest locations of all assigned beacons
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['beacon_id' => 'id'])->via('beacons')
            ->orderBy(['created_at'=>SORT_DESC]);
    }

    /**
     * @return \api\common\models\Location
     * Return latest locations of all assigned beacons
     */
    public function getLatestLocation()
    {
        return $this->hasMany(Location::className(), ['beacon_id' => 'id'])->via('beacons')
            ->orderBy(['created_at'=>SORT_DESC])->limit(1);
    }

    /**
     * @return \api\common\models\Location
     * Return latest locations of all assigned beacons
     */
    public function getLocationHistories($limit=5)
    {
        return $this->hasMany(LocationHistory::className(), ['beacon_id' => 'id'])->via('beacons')
            ->orderby(['id'=>SORT_DESC])->limit($limit);
    }
}
