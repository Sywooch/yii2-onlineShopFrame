<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/user/security/login']]
            ) : (
                ['label' => 'Welcome '.Yii::$app->user->identity->username, 'url' => ['/user/settings/profile']]
            )
        ],
    ]);
    NavBar::end();
    ?>

    <?php if (!Yii::$app->cart->isEmpty) :?>
        <div id="cartOnUpMenu">
            <a href="/products/cart">
                <?='Товаров: '.count(Yii::$app->cart->getPositions()).'<br>Cумма: '.number_format(Yii::$app->cart->cost, 2, '.', ' ').' руб.'?>
            </a>
        </div>
    <?php elseif (Yii::$app->cart->isEmpty) :?>
        <div id="cartOnUpMenu" style="display:none;">
            <a href="/products/cart">
            </a>
        </div>
    <?php endif; ?>

    <div class="container">
        <div id="breadcrumbs_ajax">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div>
        <div class="row">
            <?= $content ?>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
