<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\Strings;
use common\helpers\Activities;

use common\models\User;
use common\models\UserSchool;
use common\models\LoginForm;
use common\models\Setting;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'reset-all-data'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)  {
                            return User::can();
                        }
                    ],
                ],
            ],
            /*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($startdate = null, $enddate = null)
    {
        // check expiration day
        $user_school = UserSchool::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        if (date('Y-m-d') > $user_school->school->expired_date) {
            return $this->redirect(['site/login']);
        }

        // return Yii::$app->security->generatePasswordHash('gresik2022');
        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');

        \common\helpers\Sidebar::setActiveMenu('site', 'index');

        // Vars initiate
        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');
        // $end = new DateTime($enddate);
        // $begin = new DateTime($startdate);

        return $this->render('index', [
            'startdate' => date('d M Y', strtotime($startdate)),
            'enddate' => date('d M Y', strtotime($enddate)),
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user_school = UserSchool::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
            if ($user_school) {

                // check expiration day
                if (date('Y-m-d') <= $user_school->school->expired_date) {
                    Yii::$app->session->set('school_id', $user_school->school_id);
                    Yii::$app->session->set('school_name', $user_school->school->name);
                } else {
                    Yii::$app->session->setFlash('danger', 'Layanan BANKSEKOLAH Anda sudah melewati jatuh tempo, silakan hubungi 085649400339 untuk memperpanjang layanan');
                    return $this->redirect(['site/login']);
                }

            } else {
                Yii::$app->session->set('school_id', '');
                Yii::$app->session->set('school_name', '');
            }

            // activity log
            Activities::log('Login');

            return $this->goBack();
        } else {
            return $this->renderPartial('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        // activity log
        Activities::log('Logout');

        Yii::$app->user->logout();
        return $this->goHome();
    }

}
