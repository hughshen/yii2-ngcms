<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/app.js',
        'js/controllers.js',
    ];
    public $depends = [
        'backend\assets\AngularAsset',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];

    public function init()
    {
        parent::init();
        if (defined('YII_ENV') && YII_ENV == 'dev') {
            array_push($this->js, 'js/less.min.js');
        }
    }

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        if (defined('YII_ENV') && YII_ENV == 'dev') {
            $view->registerLinkTag([
                'rel' => 'stylesheet/less',
                'type' => 'text/css',
                'href' => \Yii::getAlias('@web') . '/css/site.less',
            ]);
        }
    }
}