<?php

namespace backend\controllers;

use common\models\Beacon;
use common\models\Caregiver;
use common\models\Missing;
use Yii;
use common\models\Resident;
use common\models\ResidentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\BeaconSearch;
use common\models\Location;
use common\models\LocationHistory;
use common\models\LocationHistorySearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\UploadedFile;

/**
 * ResidentController implements the CRUD actions for Resident model.
 */
class ResidentController extends Controller
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
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
     * Lists all Resident models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResidentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionShowMissing()
    {
        $missing = Resident::find()->where(['status' => 1]);
        $missingList = new ActiveDataProvider([
            'query' => $missing
        ]);
        $searchModel = new ResidentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider = $missingList;
        return $this->render('missing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Resident model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new LocationHistorySearch();

        $query_beacon = Beacon::find()->where(['resident_id' => $id]);
        $beaconList = new ActiveDataProvider([
            'query' => $query_beacon
        ]);

        $query_missing = Missing::find()->where(['resident_id' => $id])->orderBy('created_at', 'DEC');
        $missingList = new ActiveDataProvider([
            'query' => $query_missing
        ]);

        $query_caregivers = Caregiver::find()->where(['resident_id' => $id]);
        $caregiverList = new ActiveDataProvider([
            'query' => $query_caregivers
        ]);

        return $this->render('view', [
            'model' => $model,
            'beacons' => $beaconList,
            'missings' => $missingList,
            'caregivers' => $caregiverList,
        ]);
    }

    /**
     * Creates a new Resident model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Resident();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file) {
                $model->image_path = 'uploads/human_images/' . $model->nric . '_' . $model->fullname . '.' . $model->file->extension;
                $model->thumbnail_path = 'uploads/human_images/thumbnail_' . $model->nric . '_' . $model->fullname . '.' . $model->file->extension;
                $model->file->saveAs($model->image_path);
                $this->makeThumbnails($model->nric . '_' . $model->fullname . '.' . $model->file->extension);

            } else {
                if ($model->image_path == '') {
                    $model->image_path = "uploads/human_images/no_image.png";
                    $model->thumbnail_path = 'uploads/human_images/thumbnail_no_image.png';
                }
            }
//            if ($model->status == 1) {
//                $model->created_at = date('Y-m-d H:i:s');
//            }
//            else {
//                $model->created_at = "";
//            }
            if ($model->save()) {


                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Resident model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file) {
                $model->image_path = 'uploads/human_images/' . $model->nric . '_' . $model->fullname . '.' . $model->file->extension;
                $model->thumbnail_path = 'uploads/human_images/thumbnail_' . $model->nric . '_' . $model->fullname . '.' . $model->file->extension;
                $model->file->saveAs($model->image_path);
                $this->makeThumbnails($model->nric . '_' . $model->fullname . '.' . $model->file->extension);
//                $model->file->saveAs('uploads/human_images/'.$model->firstname.'_'.$model->lastname.'.'.$model->file->extension);
            } else {
                if ($model->image_path == '') {
                    $model->image_path = "uploads/human_images/no_image.png";
                    $model->thumbnail_path = 'uploads/human_images/thumbnail_no_image.png';
                }
            }
//            if ($model->status == 1) {
//                $model->created_at = date('Y-m-d H:i:s');
//            }
//            else {
//                $model->created_at = "";
//            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSave()
    {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $model = Resident::findOne($id);
        if ($status == $model->status) {
            $model->status = 1 - $status;
        }

        $missing = Missing::findOne(['resident_id' => $id]);
        if ($missing)
            $missing->delete();

        //$model->created_at = "";
        $model->save();
//        return true;
        return $this->redirect(['index']);

    }

    public function deleteLocation($locations)
    {
        foreach ($locations as $key => $value) {
//            var_dump($value->id);
            $location = Location::findOne($value->id);
            $location->delete();
        }
    }

    public function actionRemark()
    {
        $id = $_POST['id'];
        $remark = $_POST['remark'];
        $status = $_POST['status'];
        $model = Resident::findOne($id);
        if ($status == $model->status) {
            $model->status = 1 - $status;
        }
        $locations = ($model->locations);
        $this->deleteLocation($locations);

        $model->remark = $remark;
        $model->save();

        #Update Missing
        $check = Missing::findOne(['resident_id' => $id]);
        if ($check == null) {
            $missing = new Missing();
            $missing->resident_id = $id;
            $missing->reported_by = Yii::$app->user->id;
            $missing->remark = $remark;
            $missing->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Resident model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Resident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Resident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resident::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function makeThumbnails($imgName)
    {
        $uploadsPath = "uploads/human_images/";
        $imgPath = $uploadsPath . $imgName;
        $thumb_before_word = "thumbnail_";
        $arr_image_details = getimagesize($imgPath);
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        if ($original_width > 2 * $original_height) {
            $thumbnail_width = 200;
            $thumbnail_height = intval($original_height * 200 / $original_width);
        } else {
            $thumbnail_height = 100;
            $thumbnail_width = intval($original_width * 100 / $original_height);
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
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $original_width, $original_height);
            $imgt($new_image, $uploadsPath . $thumb_before_word . $imgName);
        }
    }
}
