<?php

namespace common\models\form;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\helpers\Html;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $captcha;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['captcha', 'required'],
            ['captcha', 'captcha'],

        ];
    }

    /**
     * Validates the username
     * This method serves as the inline validation for username.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (User::existsUsername($this->username)) {
                $this->addError($attribute, 'Username is not available. Try another one.');
            }
        }
    }

    /**
     * Validates the email
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (User::existsEmail($this->email)) {
                $this->addError($attribute, 'This email is already registered. Proceed to ' . Html::a('login', ['/site/login']) . ' or ' . Html::a("reset password", ['/site/resetPassword']));
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->role = User::ROLE_USER;
            $user->status = User::STATUS_WAIT;
            $user->generateEmailConfirmToken();

            if ($user->save()) {
                # send activation email
                Yii::$app->mailer->compose(['text' => '@common/mail/emailConfirmToken-html'], ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Email confirmation for ' . Yii::$app->name)
                    ->send();
                # use RBAC
//                $auth = Yii::$app->authManager;
//                $role = $auth->getRole(User::$roles[$user->role]);
//                $auth->assign($role, $user->getId());

                return $user;
            } else {
                $errors = $user->getErrors();
                Yii::$app->getSession()->setFlash('error', sizeof($errors) > 0 ? array_values($errors)[0] : "Unknown error.");
            }
        }

        return null;
    }
}
