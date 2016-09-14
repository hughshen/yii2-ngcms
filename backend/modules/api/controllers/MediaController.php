<?php
namespace backend\modules\api\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use backend\modules\api\models\Media;

class MediaController extends Controller
{
    public $uploadRoot;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['index', 'delete'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'delete'],
            'rules' => [
                [
                    'actions' => ['index', 'delete'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function init()
    {
        parent::init();
        $this->uploadRoot = dirname(Yii::getAlias('@app')) . '/frontend/web/uploads/';
    }

    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $model = new Media();
            $model->file = UploadedFile::getInstanceByName('file');
            if ($model->validate()) {
                $model->created_at = time();
                $model->path = $model->created_at . uniqid() . '.' . $model->file->extension;
                if ($model->save() && $model->file->saveAs($this->uploadRoot . $model->path)) {
                    return [
                        'status' => 1,
                        'message' => 'Upload success.',
                    ];
                } else {
                    return [
                        'status' => 0,
                        'message' => 'Upload wrong.',
                    ];
                }
            } else {
                return $model;
            }
        }

        $response = Media::find()
            ->orderBy('id DESC')
            ->all();

        return $response;
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            $model = Media::findOne((int)Yii::$app->request->post('id'));
            if ($model->delete() && unlink($this->uploadRoot . $model->path)) {
                return [
                    'status' => 1,
                    'message' => 'delete success.',
                ];
            } else {
                return [
                    'status' => 0,
                    'message' => 'delete error.',
                ];
            }
        } else {
            return 'error';
        }
    }
}