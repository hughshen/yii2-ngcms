<?php
namespace backend\modules\api\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use backend\modules\api\models\Slider;

/**
 * Site controller
 */
class SliderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['index'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index'],
            'rules' => [
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
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
}