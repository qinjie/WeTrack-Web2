<?php

namespace common\models;

use common\components\TokenHelper;
use common\models\query\UserQuery;
use Symfony\Component\CssSelector\Parser\Token;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\Link;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\filters\RateLimitInterface;
use yii\web\Linkable;
use yii\db\ActiveRecord;
use fedemotta\awssdk;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $access_token
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $phone_number
 * @property string $role
 * @property integer $status
 * @property string $allowance
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Caregiver[] $caregivers
 * @property DeviceToken[] $deviceTokens
 * @property Location[] $locations
 * @property LocationHistory[] $locationHistories
 * @property Missing[] $missings
 * @property Usertoken[] $usertokens
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_BLOCKED = 1;
    const STATUS_WAIT = 5;
    const STATUS_ACTIVE = 10;
    const STATUS_CRASHED = -1;

    public static $roles = [40 => 'admin', 30 => 'admin', 20 => 'family', 10 => 'Volunteer', 5 => 'anonymous', 2 => 'Raspberry Pi'];

    const ROLE_RPI = 2;
    const ROLE_ANONYMOUS = 5;
    const ROLE_USER = 10;
    const ROLE_MANAGER = 20;
    const ROLE_ADMIN = 30;
    const ROLE_MASTER = 40;
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required', 'message' => 'Please enter a username.'],
            ['username', 'unique'],
            [['role', 'status', 'allowance', 'timestamp'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            [['created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['email', 'password_hash', 'password_reset_token', 'email_confirm_token'], 'string', 'max' => 255],
            [['auth_key', 'access_token'], 'string', 'max' => 32],
            [['phone_number'], 'string', 'max' => 20],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'This email address has already been taken.'],
            // the email attribute should be a valid email address
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim'],
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
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'access_token' => 'Access Token',
            'password_reset_token' => 'Password Reset Token',
            'email_confirm_token' => 'Email Confirm Token',
            'phone_number' => 'Phone Number',
            'role' => 'Role',
            'status' => 'Status',
            'allowance' => 'Allowance',
            'timestamp' => 'Timestamp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this-> status);
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_BLOCKED => 'Blocked',
            self::STATUS_WAIT => 'Pending Confirmation',
            self::STATUS_CRASHED => 'Crashed',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $id = TokenHelper::authenticateToken($token, true);
        if ($id){
            return static::findIdentity($id);
        }else{
            return null;
        }
    }

    public static function findByUsername($username, $status = NULL)
    {
        if (!$status) $status = self::STATUS_ACTIVE;
        return static::findOne(['username' => $username, 'status' => $status]);
    }

    public static function findByEmail($email, $status = NULL)
    {
        if (!$status) $status = self::STATUS_ACTIVE;
        return static::findOne(['email' => $email, 'status' => $status]);
    }

    public static function existsUsername($username)
    {
        return static::find()->where(['username' => $username])->exists();
    }

    public static function existsEmail($email)
    {
        return static::find()->where(['email' => $email])->exists();
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)){
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
            ]
        );
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)){
            return false;
        }

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token'],
    $fields['updated_at'], $fields['created_at']);
        return $fields;
    }
    #For HATEOAS
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
    }

    public function beforeSave($insert)
    {
        if (isset($this->password))
            $this->setPassword($this->password);
        if (parent::beforeSave($insert)){
            if ($insert)
                $this->generateAuthKey();
            return true;
        }
        return false;
    }

    #To add the error message when unknown attributes are received.

    public function onUnsafeAttribute($name, $value)
    {
        parent::onUnsafeAttribute($name, $value);

        $this->addError(
            $name,
            Yii::t('app','Unknown parameter `{name}`', ['name' => $name])
        );
    }

    public function clearErrors($attribute = null)
    {
        if (!$attribute || !isset($this->attributes[$attribute]))
            return;

        parent::clearErrors($attribute); // TODO: Change the autogenerated stub
    }

    #Implementation for RateLimitInterface
    /**
     * Returns the maximum number of allowed requests and the window size.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     * and the second element is the size of the window in seconds.
     */
    public
    function getRateLimit($request, $action)
    {
        ## HARDCODED
        //-- 300 times for every 10 minutes
        return [300, 600];
    }

    /**
     * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the number of allowed requests,
     * and the second element is the corresponding UNIX timestamp.
     */
    public
    function loadAllowance($request, $action)
    {
        if (is_null($this->allowance || is_null($this->timestamp))) {
            ## HARDCODED
            $this->timestamp = 0;
            $this->save(false, ['allowance', 'timestamp']);
        }
        return [$this->allowance, $this->timestamp];
    }

    /**
     * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @param integer $allowance the number of allowed requests remaining.
     * @param integer $timestamp the current timestamp.
     */
    public
    function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->timestamp = $timestamp;
        $this->save(false, ['allowance', 'timestamp']);
    }

    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->refresh();
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaregivers()
    {
        return $this->hasMany(Caregiver::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceTokens()
    {
        return $this->hasMany(DeviceToken::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories()
    {
        return $this->hasMany(LocationHistory::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissings()
    {
        return $this->hasMany(Missing::className(), ['reported_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsertokens()
    {
        return $this->hasMany(Usertoken::className(), ['user_id' => 'id']);
    }
}
