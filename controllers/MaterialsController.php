<?php

namespace app\controllers;

use app\models\custom\AuxData;
use app\models\Locations;
use app\models\Movements;
use app\models\Orders;
use app\models\RelocationForm;
use app\models\Stocks;
use yii;
use app\models\Materials;
use app\models\User;
use app\models\search\MaterialsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaterialsController implements the CRUD actions for Materials model.
 */
class MaterialsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['site/login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'matchCallback' => function ($rule, $action) {
                            return User::checkRights(Yii::$app->user->identity['role'], $this->uniqueId, $action->id);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Materials models.
     * @return mixed
     * @throws yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        };

        //AuxData::updateAllQuantitites();
        $afterDeleteUrl = !!Yii::$app->request->getUrl() ? Yii::$app->request->getUrl() : ['index'];
        Yii::$app->user->setReturnUrl($afterDeleteUrl);
        $searchModel = new MaterialsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', compact("searchModel", "dataProvider"));
    }

    /**
     * Displays a single Materials model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
            $model = $this->findModel($id);
            $qties = $model->getQuantities();
            $loc = $model->getLocations();
            $movements_data = $model->getMovements()->orderBy('transaction_date DESC')->all();
            $mov_labels = (Movements::getLabels());
            $ord_labels = (Orders::getLabels());
            $lists['directions'] = AuxData::getDirections();
            $lists['statuses'] = AuxData::getOrderStatus();
            return $this->render('view', compact ("model", "movements_data", "lists", "qties", "loc", "mov_labels", "ord_labels"));
    }

    /**
     * Creates a new Materials model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Materials();
        $lists['units'] = AuxData::getUnits();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', compact("model", "lists"));
        }
    }

    /**
     * Updates an existing Materials model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $qties = $model->getQuantities();
        $lists['units'] = AuxData::getUnits();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', compact("model", "lists", "qties"));
        }
    }

    /**
     * Deletes an existing Materials model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->user->returnUrl);
    }

    /**
     * @param integer $id
     * @param integer | null $stid
     * @return string|yii\web\Response
     */
    public function actionChangeLocation($id, $stid = null)
    {
        $model = new RelocationForm([
            'materials_id' => $id,
            'stocks_id_old' => $stid

        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id, 'tab' => 'location']);
        } else {
            return $this->render('relocation_form', compact('model'));
        }
    }

    /**
     * Finds the Materials model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Materials the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Materials::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
