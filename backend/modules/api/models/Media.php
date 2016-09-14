<?php
namespace backend\modules\api\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Media extends ActiveRecord
{
    public $file;

    public static function tableName()
    {
        return '{{%media}}';
    }

    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg']],
        ];
    }
}
