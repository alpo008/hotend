<?php

namespace app\controllers;

use app\models\custom\AuxData;
use app\models\custom\SendMessage;
use app\models\custom\TempFile;
use app\models\Locations;
use app\models\Materials;
use app\models\search\MissedOrdersSearch;
use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;

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
                'only' => ['logout'],
                'rules' => [
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->actionLogin();
        };

        AuxData::updateAllQuantitites();

        $searchModel = new MissedOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $urgents = AuxData::getUrgents();
        $output_data = TempFile::getInstance();
        $download_data = array();
        $mail_labels = AuxData::getUrgentsLabels(Materials::getLabels());
        $mail_list = NULL;

        if(!!$urgents){
            $mail_list = AuxData::updateUrgents($urgents);
            $output_data->saveTemp([
                'name' => 'temp',
                'ext' => 'csv',
                'content' => $mail_list,
                'labels' => $mail_labels,
            ]);

            $urgents_labels = AuxData::getLabels([
                ['materials',
                ['id', 'ref', 'name', 'qty', 'minqty', 'unit', 'type', 'gruppa']]
            ]);
                $output_data->saveTemp([
                'name' => 'urgents',
                'ext' => 'xls',
                'content' => $urgents,
                'labels' => $urgents_labels,
            ]);
            $download_data[Yii::t('app', 'Urgent orders')] = 'urgents.xls';
            if(!!$mail_list){
                $output_data->saveTemp([
                    'name' => 'emails',
                    'ext' => 'xls',
                    'content' => $mail_list,
                    'labels' => $mail_labels,
                ]);
                $download_data[Yii::t('app', 'Urgent messages')] = 'emails.xls';
            }
        }
        $materials_labels =  AuxData::getLabels([
            ['materials',['id', 'ref', 'name', 'qty', 'minqty', 'unit', 'type', 'gruppa']],
            ['stocks', ['placename']]
        ]);
        $output_data->saveTemp([
            'name' => 'materials',
            'ext' => 'xls',
            'content' => AuxData::getFullTable(),
            'labels' => $materials_labels,
        ]);
        $download_data[Yii::t('app', 'Materials')] = 'materials.xls';

        $orders_labels = AuxData::getLabels([
            ['orders',['order_date' , 'docref']],
            ['materials', ['ref', 'name']],
            ['orders',['qty', 'status']],
            ]);
        $output_data->saveTemp([
            'name' => 'orders',
            'ext' => 'xls',
            'content' => AuxData::getOrders(),
            'labels' => $orders_labels,
        ]);
        $download_data[Yii::t('app', 'Orders')] = 'orders.xls';

        $orders_labels = AuxData::getLabels([
            ['stocks',['placename' , 'description']],
            ['materials', ['ref', 'name', 'qty']],
        ]);
        $output_data->saveTemp([
            'name' => 'stock',
            'ext' => 'xls',
            'content' => AuxData::getStockTable(),
            'labels' => $orders_labels,
        ]);
        $download_data[Yii::t('app', 'Stocks')] = 'stock.xls';

        $message = (!$mail_list) ? true : false;

        $lists['statuses'] = AuxData::getOrderStatus();
        $lists['downloads'] = $download_data;
        return $this->render('index', compact ("searchModel", "dataProvider", "lists", "message"));
    }

    public function actionDownload($name)
    {
        $f_instance = TempFile::getInstance();
        $path = $f_instance->getStoragePath();
        if (!preg_match('/^[a-z0-9_]+\.[a-z0-9]+$/i', $name) || !is_file("$path/$name")) {
            throw new NotFoundHttpException(Yii::t('app', 'The file does not exists.'));
        }
        return Yii::$app->response->sendFile("$path/$name", date('Y-m-d') . '_' . $name);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $output_data = TempFile::getInstance();
        $attachment = $output_data->getStoragePath(). 'temp.csv';
        if (is_file($attachment)){
            if(SendMessage::sendNotification()){
                unlink ($attachment);
            }
        }
        
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
