<?php

namespace backend\controllers;

use common\components\AccessRule;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'allow' => true,
                        'roles' => [\common\models\User::ROLE_MANAGER, \common\models\User::ROLE_ADMIN, \common\models\User::ROLE_MASTER],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = User::find()->where(['id' => $id])->one();
        if ($query['role'] >= Yii::$app->user->identity->role){
            throw new UserException("You can't see user who have role equal or greater than you");
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new User();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file_path = 'uploads/human_images/'.$model->username.'.'.$model->file->extension;
            $model->thumbnail_path = 'uploads/human_images/thumbnail_'.$model->username.'.'.$model->file->extension;
            if ($model->save()) {
                $model->file->saveAs('uploads/human_images/'.$model->username.'.'.$model->file->extension);
                $this->makeThumbnails($model->username.'.'.$model->file->extension);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function makeThumbnails($imgName)
    {
        $uploadsPath = "uploads/human_images/";
        $imgPath = $uploadsPath.$imgName;
        $thumb_before_word = "thumbnail_";
        $arr_image_details = getimagesize($imgPath);
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        if ($original_width > 2*$original_height) {
            $thumbnail_width = 200;
            $thumbnail_height = intval($original_height*200/$original_width);
        } else {
            $thumbnail_height = 100;
            $thumbnail_width = intval($original_width*100/$original_height);
        }
        if ($arr_image_details[2] == 1) {
            $imgt = "imagegif";
            $imgcreatefrom = "imagecreatefromgif";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "imagejpeg";
            $imgcreatefrom = "imagecreatefromjpeg";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "imagepng";
            $imgcreatefrom = "imagecreatefrompng";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom($imgPath);
            $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            imagealphablending( $new_image, false );
            imagesavealpha( $new_image, true );
            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $original_width, $original_height);
            $imgt($new_image, $uploadsPath.$thumb_before_word.$imgName);
        }
    }
}