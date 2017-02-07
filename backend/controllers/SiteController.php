<?php
namespace backend\controllers;

use common\components\AccessRule;
use common\models\Beacon;
use common\models\form\ResetPasswordForm;
use common\models\form\ResetPasswordRequestForm;
use common\models\LocationHistory;
use common\models\Location;
use common\models\Resident;
use common\models\User;
use common\models\form\ChangePasswordForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\form\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['login', 'request-password-reset', 'reset-password', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index','change-password', 'account'],
                        'allow' => true,
                        'roles' => [\common\models\User::ROLE_MANAGER, \common\models\User::ROLE_ADMIN, \common\models\User::ROLE_MASTER],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $numberofResident = Resident::find()->count();
        $numberofBeacon = Beacon::find()->count();
        $numberofMissingResident = Resident::find()->where(['status' => 1])->count();
        $model = Resident::find()->where(['status' => 1])->all();
        $count = count($model);
        $locations = [];
//        var_dump($model);
        $cnt = 0;
        foreach ($model as $m){
            if ($m->latestLocation) $cnt++;


        }
        return $this->render('index',[
            'resident' => $numberofResident,
            'beacon' => $numberofBeacon,
            'missing' => $numberofMissingResident,
            'model' => $model,
            'count' => $cnt
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->role > User::ROLE_USER){
                return $this->goBack();
            }
            else{
                Yii::$app->user->logout();
                throw new UserException("You are not allow to go to this website.");
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAccount()
    {
        try {
            return $this->render('account', [
                'model' => User::findOne(Yii::$app->user->identity->getId()),
            ]);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }


    public function actionChangePassword()
    {
        /*@var $user \common\models\User */
        $user = User::findOne(Yii::$app->user->identity->getId());
        $model = new ChangePasswordForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->getSession()->setFlash('success', 'You have changed your password.');
            return $this->redirect(['index']);
        } else {
            return $this->render('changePassword', [
                'model' => $model,
            ]);
        }
    }

    public function actionRequestPasswordReset()
    {
        $model = new ResetPasswordRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Error! Failed to send reset-password email.');
            }
        }

        return $this->render('requestPasswordReset', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Thanks! Your passwords is changed.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
