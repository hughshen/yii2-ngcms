<?php

namespace backend\models;

use Yii;

class Slider extends \yii\db\ActiveRecord
{
    public $file;

    public static function tableName()
    {
        return '{{%slider}}';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['ordering', 'status'], 'integer'],
            ['ordering', 'default', 'value' => 0],
            [['title', 'caption', 'img_url', 'link_url'], 'string', 'max' => 255],
            [['link_target'], 'string', 'max' => 10],
            [['created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'default', 'value' => time()],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }
}
