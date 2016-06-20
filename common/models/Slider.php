<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Slider extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%slider}}';
    }
}
