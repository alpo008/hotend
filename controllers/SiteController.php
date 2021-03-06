<?php

namespace app\controllers;

use app\models\custom\AuxData;
use app\models\custom\MessageData;
use app\models\custom\SendMessage;
use app\models\custom\TempFile;
use app\models\Materials;
use app\models\Movements;
use app\models\search\MissedOrdersSearch;
use Symfony\Component\CssSelector\XPath\Extension\HtmlExtension;
use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\HttpException;
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
        }elseif (Yii::$app->user->identity->role === 'ADMIN' || Yii::$app->user->identity->role === 'ENGINEER'){

        //AuxData::updateAllQuantitites();

/*            $nf = fopen("/home/alpo/Projects/hotend/data/all_stock17.csv", "r");
            while ($dt = fgetcsv($nf)){
                $ref = (int) $dt[0];
                $name = trim ($dt[1]);
                $gruppa = trim ($dt[2]);
                $type = trim ($dt[3]);
                $a = new Materials();
                $a->ref = $ref;
                $a->name = $name;
                $a->type = $type;
                $a->gruppa = $gruppa;
                $a->unit = 'ШТ';
                $a->minqty = 0;
                $a->qty = 0;
                $a->save();


            }*/

        $backupPath = $this->prepareDbBackup();
        $searchModel = new MissedOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $messagesProvider = MessageData::getInstance();
        $mess_data = $messagesProvider->prepareDownloads();
        $lists = $mess_data['lists'];
        $message = $mess_data['message'];
        $recents = Movements::find()
            ->select(['materials_id', 'COUNT(materials_id) AS countmat'])
            ->where(['direction' => 0])
            ->limit(5)
            ->orderBy(['countmat' => SORT_DESC])
            ->groupBy('materials_id')
            ->joinWith('materials')
            ->asArray()
            ->all();
        $lists['recent'] = array_column($recents, 'materials');

        return $this->render('index', compact ("searchModel", "dataProvider", "lists", "message", "backupPath"));
        }elseif (Yii::$app->user->identity->role === 'OPERATOR'){
            $lists['materials'] = AuxData::getMaterials();
            $recents = Movements::find()
                ->select(['materials_id', 'COUNT(materials_id) AS countmat'])
                ->where(['direction' => 0])
                ->limit(10)
                ->orderBy(['countmat' => SORT_DESC])
                ->groupBy('materials_id')
                ->joinWith('materials')
                ->asArray()
                ->all();
            $lists['recent'] = array_column($recents, 'materials');

            return $this->render('fastsearch', compact ("lists"));
        }else{
            return new HttpException(404, 'The requested Item could not be found.');
        }
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

    public function actionBackup ($fullPath)
    {
        return Yii::$app->response->sendFile("$fullPath", date('Y-m-d') . '_' . 'database_backup.sql');
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

    public function prepareDbBackup()
    {
        $today = date('Y-m-d');
        $backupPath = str_replace('web', 'data/backups/' . $today . '/' . $today . '_hotendspares.sql', $_SERVER['DOCUMENT_ROOT']);

        return (is_file($backupPath)) ? $backupPath : null;
    }
}
