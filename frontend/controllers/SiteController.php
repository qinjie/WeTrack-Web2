<?php
namespace frontend\controllers;

use common\models\CommonFunction;
use common\models\form\ChangePasswordForm;
use common\models\form\LoginForm;
use common\models\form\ResetPasswordRequestForm;
use common\models\form\SignupForm;
use common\models\form\EmailConfirmForm;
use common\models\form\ResetPasswordForm;
use common\models\form\ChangeEmailForm;
use common\models\User;
use common\models\form\ContactForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

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
                'only' => ['index', 'logout', 'change-password', 'signup', 'request-password-reset'],
                'rules' => [
                    [
                        'actions' => ['signup', 'request-password-reset'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout', 'change-password'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $username = Yii::$app->getSession()->get('user.username', null);
        if (!is_null($username)) {
            $model->username = $username;
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Please confirm your Email.');
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionConfirmEmail($token)
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', 'Thank you! Your email address is confirmed.');
            // Forward the username to next page
            Yii::$app->getSession()->set('user.username', $model->getUser()->username);
            return $this->redirect(Yii::$app->urlManager->createUrl('site/login'));
        } else {
            Yii::$app->getSession()->setFlash('error', 'Error! Failed to confirm your email.');
        }

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
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

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
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

    public function actionChangeEmail()
    {
        $user = User::findOne(Yii::$app->user->identity->getId());
        $model = new ChangeEmailForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('success', 'Thanks! Your email is changed.');

            return $this->redirect(['index']);
        } else {
            return $this->render('changeEmail', [
                'model' => $model,
            ]);
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

    public function actionTestImagine()
    {
        #http://git.yiisoft.com/wiki/757/how-to-use-imagine-crop-thumb-effects-for-images-on-yii2/

        $folder = Yii::getAlias('@upload/');
        $file = $folder . 'test.png';

        if (file_exists($file)) {
            Image::crop($file, 200, 200, [5, 5])->save($folder . 'test-crop.png', ['quality' => 80]);

            Image::thumbnail($file, 120, 120)->save($folder . 'test-thumb.png', ['quality' => 80]);
            return "Done";
        }
        return "Image file not found: " . $file;
    }


}
