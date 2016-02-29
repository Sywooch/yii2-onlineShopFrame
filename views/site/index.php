<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = Yii::$app->name;
Yii::$app->session->remove('forBreadCrumbs');
?>

<div class="site-index">

    <div class="jumbotron">
        <h1> Welcom to <?=$this->title?></h1>
        <p>
            <?='Hello or Picture place here'?>
        </p>
    </div>

    <div class="body-content">
        <p>Новые поступления</p>
        <div class="row">
            <?php echo ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'itemView' => '..\products\_catalogView',
                'itemOptions' => ['class'=>'ajaxLoadOut'],
            ]);?>
        </div>

    </div>
</div>
