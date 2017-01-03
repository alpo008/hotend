<?php

namespace app\controllers;

use app\models\custom\AuxData;
use app\models\Materials;
use yii;
use app\models\Movements;
use app\models\search\MovementsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MovementsController implements the CRUD actions for Movements model.
 */
class MovementsController extends Controller
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
        ];
    }

    /**
     * Lists all Movements models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MovementsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $lists['directions'] = AuxData::getDirections();


        return $this->render('index', compact ('searchModel', 'dataProvider', 'lists'));
    }

    /**
     * Displays a single Movements model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $materials_data = $model->getMaterials()->select(['name', 'unit', 'type', 'gruppa'])->one();
        $lists['directions'] = AuxData::getDirections();

        return $this->render('view', compact ("model", "lists"));
    }

    /**
     * Creates a new Movements model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Movements();
        $lists['directions'] = AuxData::getDirections();
        $lists['materials'] = AuxData::getMaterials();
        $lists['stocks'] = AuxData::getStocks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', compact ("model", "lists"));
        }
    }

    /**
     * Updates an existing Movements model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lists['directions'] = AuxData::getDirections();
        $lists['materials'] = AuxData::getMaterials();
        $lists['stocks'] = AuxData::getStocks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', compact ("model", "lists"));
        }
    }

    /**
     * Deletes an existing Movements model.
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
     * Finds the Movements model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Movements the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Movements::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
