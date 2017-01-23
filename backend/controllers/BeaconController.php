<?php

namespace backend\controllers;

use common\models\LocationHistory;
use common\models\LocationHistorySearch;
use Yii;
use common\models\Beacon;
use common\models\BeaconSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\AccessRule;

/**
 * BeaconController implements the CRUD actions for Beacon model.
 */
class BeaconController extends Controller
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
     * Lists all Beacon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BeaconSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Beacon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new LocationHistorySearch();
        $query = LocationHistory::find()->where(['beacon_id' => $id])->limit(5);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

//        $dataProvider = $searchModel->search(['beacon_id' => $id]);
//        $dataProvider = $searchModel->search(['id' => 1]);
//        $dataProvider = LocationHistory::find()->where('beacon_id' == $id)->all();
        return $this->render('view', [
            'model' => $model,
//            'location_history' => $location_history,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Beacon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Beacon();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Beacon model.
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
    public function actionSave(){
//        $model = $this->findModel($id);
//        var_dump($model);
//        $model->status = 1 - $model->status;
//        $model->save();
//        return $this->redirect(['index']);


//        if (Yii::$app->request->isAjax) {
//            $data = Yii::$app->request->post();
//            $id= explode(":", $data['id']);
//            $st= explode(":", $data['status']);
//            //$status = ? 0 : 1;
//            var_dump("hoa");
//            $model = Beacon::findOne($id);
//            $model->status = 1 - $st;
//            $model->save();
//            return [
//                'status' => $st,
//            ];
//        }

            $id = $_POST['id'];
        $status = $_POST['status'];
        $model = Beacon::findOne($id);
        $model->status = 1 - $status;
        $model->save();
        return $this->redirect(['index']);
//        return $status;
//        return ($this->input->post('form_id'));

    }

    /**
     * Deletes an existing Beacon model.
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
     * Finds the Beacon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Beacon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Beacon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
