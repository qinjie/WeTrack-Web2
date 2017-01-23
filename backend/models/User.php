<?php

namespace backend\models;

use common\models\Location;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $access_token
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property integer $role
 * @property integer $status
 * @property integer $allowance
 * @property integer $timestamp
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property FloorManager[] $floorManagers
 * @property Usertoken[] $usertokens
 */
class User extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['role', 'status', 'allowance', 'timestamp', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'email_confirm_token'], 'string', 'max' => 255],
            [['auth_key', 'access_token'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'access_token' => 'Access Token',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'email_confirm_token' => 'Email Confirm Token',
            'role' => 'Role',
            'status' => 'Status',
            'allowance' => 'Allowance',
            'timestamp' => 'Timestamp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'roleName' => 'Role',
            'statusName' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloorManagers()
    {
        return $this->hasMany(FloorManager::className(), ['userid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsertokens()
    {
        return $this->hasMany(Usertoken::className(), ['user_id' => 'id']);
    }

    /**
     * @return int
     */
    public function getRoleName()
    {
        if ($this->role == 20) return 'Manager';
        if ($this->role == 30) return 'Admin';
        if ($this->role == 40) return 'Master';
        return 'User';
    }

    public function getStatusName(){
        if ($this->status == 0) return "Deleted";
        if ($this->status == 1) return "Blocked";
        if ($this->status == 5) return "Wait";
        return "Active";
    }

    public function getCoorx(){
        $query = Location::find()->where(['user_id' => $this->id])->orderBy('created_at DESC')->one();
        return $query['coorx'];
    }

    public function getCoory(){
        $query = Location::find()->where(['user_id' => $this->id])->orderBy('created_at DESC')->one();
        return $query['coory'];
    }

    public function getLastTime(){
        $query = Location::find()->where(['user_id' => $this->id])->orderBy('created_at DESC')->one();
        return $query['created_at'];
    }

    public function getSpeed(){
        $query = Location::find()->where(['user_id' => $this->id])->orderBy('created_at DESC')->one();
        return $query['speed'];
    }

    public function getLastFloor(){
        $query = Location::find()->where(['user_id' => $this->id])->orderBy('created_at DESC')->one();
        $rs = Floor::find()->where(['id' => $query['floor_id']])->one();
        return $rs['label'];
    }

    public function getAzimuth(){
        $query = Location::find()->where(['user_id' => $this->id])->orderBy('created_at DESC')->one();
        return $query['azimuth'];
    }
}
