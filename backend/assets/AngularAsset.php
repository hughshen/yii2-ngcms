<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AngularAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'https://cdn.bootcss.com/angular.js/1.5.7/angular.min.js',
        'https://cdn.bootcss.com/angular.js/1.5.7/angular-route.min.js',
        'https://cdn.bootcss.com/angular.js/1.5.7/angular-animate.min.js',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];
}
