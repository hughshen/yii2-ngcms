<?php
namespace backend\controllers;

use Yii;
use common\models\Slider;
use backend\models\Manager;
use backend\models\ManagerForm;
use backend\models\SignupForm;
use frontend\models\ContactForm;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['dashboard'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['dashboard'],
            'rules' => [
                [
                    'actions' => ['dashboard'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $model = new ManagerForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return ['access_token' => Yii::$app->user->identity->getAuthKey()];
        } else {
            $model->validate();
            return $model;
        }
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->signup()) {
            Yii::$app->user->login(Manager::findByUsername($model->username), 0);
            return ['access_token' => Yii::$app->user->identity->getAuthKey()];
        } else {
            $model->validate();
            return $model;
        }
    }

    public function actionDashboard()
    {
        $response = [
            'username' => Yii::$app->user->identity->username,
            'access_token' => Yii::$app->user->identity->getAuthKey(),
        ];
        return $response;
    }

    public function actionSlider()
    {

        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            if (($model = Slider::findOne($id)) === null) {
                $model = new Slider();
            }

            if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

                if ($model->save()) {
                    $response = [
                        'status' => 1,
                        'message' => 'Save success.'
                    ];
                } else {
                    $response = [
                        'status' => 0,
                        'message' => 'Something wrong.'
                    ];
                }

                return $response;
            } else {
                $model->validate();
                return $model;
            }
        }

        $id = Yii::$app->request->get('id');

        if ($id) {
            $response = Slider::findOne($id);
        } else {
            $response = Slider::find()->all();
        }

        return $response;
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                $response = [
                    'flash' => [
                        'class' => 'success',
                        'message' => 'Thank you for contacting us. We will respond to you as soon as possible.',
                    ]
                ];
            } else {
                $response = [
                    'flash' => [
                        'class' => 'error',
                        'message' => 'There was an error sending email.',
                    ]
                ];
            }
            return $response;
        } else {
            $model->validate();
            return $model;
        }
    }
}