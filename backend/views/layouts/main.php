<?php
use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="app">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Yii2 with Angular</title>
    <?php $this->head() ?>
</head>
<body ng-controller="MainController" ng-cloak>
<?php $this->beginBody() ?>

<div class="nav-block" ng-show="loggedIn()">
    <h2>Yii2 with Angular</h2>
    <ul class="menu-list">
        <li ng-class="{ 'active': menuActive('/')}">
            <a href="#/">Home</a>
        </li>
        <li ng-class="{ 'active': menuActive('/slider') }">
            <a href="#/slider">Slider</a>
        </li>
        <li ng-click="logout()">
            <a href="#">Logout</a>
        </li>
    </ul>
</div>

<div ng-class="{ 'content-block': loggedIn() }" class="flex1 column" ng-view></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
