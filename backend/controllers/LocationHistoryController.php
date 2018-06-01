<?php

namespace backend\controllers;

use Yii;
use common\models\LocationHistory;
use common\models\LocationHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\AccessRule;

/**
 * LocationHistoryController implements the CRUD actions for LocationHistory model.
 */
class LocationHistoryController extends Controller
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
     * Lists all LocationHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getAddress($request_url){

        $data = @file_get_contents($request_url);
        $jsondata = json_decode($data,true);
        $d = $jsondata['results'][0]['address_components'];
        $result = "";
        $first = true;
        foreach ($d as $item){
            if ( !$first )
            {
                $result .=  ", ";
            }

            $first = FALSE;
            //echo( $city->name );
            $result .=  $item['long_name'];// . ",";
        }
//        $res = implode(",",$result);
        return ($result);
    }
    /**
     * Displays a single LocationHistory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $key_api = Yii::$app->params['google_geocoding_key'];
        $key = "AIzaSyA13kujZA51OzrcdJOyOngtPG13xxKsA1U";
        $request_url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $model->latitude . "," . $model->longitude . "&key=" . $key_api;
        $url = "https://www.google.com/maps/place/" . $model->latitude . "," . $model->longitude;
        $place = "https://www.google.com/maps/embed/v1/place?key=" .$key . htmlspecialchars ('&').  'q='
            . $model->latitude . "," . $model->longitude . "&zoom=18";
        //$address = $this->getAddress($request_url);
        return $this->render('view', [
            'model' => $model,
            //'address' => $address,
            'place' => $place,
            'url' => $url
        ]);
    }

    /**
     * Creates a new LocationHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LocationHistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LocationHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LocationHistory model.
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
     * Finds the LocationHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocationHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocationHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
