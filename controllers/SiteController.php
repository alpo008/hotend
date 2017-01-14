<?php

namespace app\controllers;

use app\models\custom\AuxData;
use app\models\custom\SendMail;
use app\models\custom\TempFile;
use app\models\search\MissedOrdersSearch;
use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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

        $searchModel = new MissedOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $urgents = AuxData::getUrgents();
        $output_data = TempFile::getInstance();

        $mail_list = NULL;
        if(!!$urgents){
            $mail_list = AuxData::updateUrgents($urgents);
            $output_data->writeCsv($mail_list);
        }

        $attachment = $output_data->getStoragePath();
        if (!!file($attachment)){
            if(SendMail::sendNotification($attachment)){
                unlink ($attachment);
            }
        }

        $message = (!$mail_list) ? true : false;

        $lists['statuses'] = AuxData::getOrderStatus();
        return $this->render('index', compact ("searchModel", "dataProvider", "lists", "message"));
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
        $attachment = $output_data->getStoragePath();
        if (!!file($attachment)){
            if(SendMail::sendNotification($attachment)){
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
