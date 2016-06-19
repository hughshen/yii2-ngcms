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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body ng-controller="MainController" ng-cloak>
<?php $this->beginBody() ?>

<div class="column">
    <header class="row flex-between bg-primary" ng-show="loggedIn()">
        <h2>Yii2 with Angular</h2>
        <ul class="menu-list">
            <li data-match-route="/$">
                <a href="#/">Home</a>
            </li>
            <li ng-click="logout()">
                <a href="">Logout</a>
            </li>
        </ul>
    </header>

    <div class="page-content" ng-view></div>

</div>

<footer ng-show="loggedIn()">
    <p>&copy; My Company <?= date('Y') ?></p>
    <p><?= Yii::powered() ?></p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
