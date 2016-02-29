<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
//use yii\widgets\DetailView;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $related app\models\Products */
$this->registerJs("
        $('.imagesOfGoods').fancybox({
            openEffect	: 'none',
            closeEffect	: 'none',
            padding     : 0,         
        });
        ", $this::POS_READY);

$this->title = $model->name.' - '.Yii::$app->name;
$xleb = Yii::$app->session->get('forBreadCrumbs');
//Yii::$app->session->remove('forBreadCrumbs');

if ($xleb == null){ // случай захода в товар с главной странице
    $this->params['breadcrumbs'] = [];
}else{
    /*
    * Перемещение по хлебным крошкам
    */
    if ($xleb[0] == '0' AND $xleb[1] != null AND $xleb[2] == null){ // случай захода в товар из просмотра группы для 1уровневой вложенности
        $this->params['breadcrumbs'][] = ['label' => $xleb[4], 'url' => ['products/catalog', 'topGrName' => '0', 'grName' => $xleb[1]]];
    }

    if ($xleb[0] != null AND ($xleb[1] != null AND $xleb[1] != '0') AND $xleb[2] == null){ // случай захода в товар из просмотра под группы
        $this->params['breadcrumbs'][] = ['label' => $xleb[3], 'url' => ['products/catalog', 'topGrName' => $xleb[0], 'grName' =>'0']];
        $this->params['breadcrumbs'][] = ['label' => $xleb[4], 'url' => ['products/catalog', 'topGrName' => $xleb[0], 'grName' => $xleb[1]]];
    }
    if ($xleb[0] != null AND $xleb[1] == '0' AND $xleb[2] == null){ // случай захода в товар из просмотра топ группы
        $this->params['breadcrumbs'][] = ['label' => $xleb[3], 'url' => ['products/catalog', 'topGrName' => $xleb[0], 'grName' =>'0']];
    }/*
    if ($xleb[1] == '0' AND $xleb[2] != null AND $xleb[3] == null){ // случай захода в товар из просмотра группы для 1уровневой вложенности
        $this->params['breadcrumbs'][] = ['label' => $xleb[5], 'url' => ['products/catalog', 'topGrName' => $xleb[1], 'grName' => $xleb[2]]];
    }
    if ($xleb[1] == '0' AND $xleb[2] != null AND $xleb[3] != null){ // случай захода в товар из просмотра категории для 1уровневой вложенности
        $this->params['breadcrumbs'][] = ['label' => $xleb[5], 'url' => ['products/catalog', 'topGrName' => $xleb[1], 'grName' => $xleb[2]]];
        $this->params['breadcrumbs'][] = ['label' => $xleb[6], 'url' => ['products/index', 'topGrName' => $xleb[1], 'grName' => $xleb[2], 'catName' => $xleb[3]]];
    }*/
    /*
     * Перемещение по меню каталога
     */
    if ($xleb[0] == null AND $xleb[1] == null AND $xleb[2] != null){ // случай захода в товар из просмотра категории без группы
        $this->params['breadcrumbs'][] = ['label' => $xleb[5], 'url' => ['products/index', 'catName' => $xleb[2]]];
    }
    if ($xleb[0] == null AND $xleb[1] != null AND $xleb[2] != null){ // случай захода в товар из просмотра  категории в группе
        $this->params['breadcrumbs'][] = ['label' => $xleb[4], 'url' => ['products/catalog', 'topGrName' => '0', 'grName' => $xleb[1]]];
        $this->params['breadcrumbs'][] = ['label' => $xleb[5], 'url' => ['products/index', 'grName' => $xleb[1], 'catName' => $xleb[2]]];
    }
    if ($xleb[0] != null AND $xleb[1] != null AND $xleb[2] != null){ // случай захода в товар из просмотра категории в топ группе
        $this->params['breadcrumbs'][] = ['label' => $xleb[3], 'url' => ['products/catalog', 'topGrName' => $xleb[0], 'grName' =>'0']];
        $this->params['breadcrumbs'][] = ['label' => $xleb[4], 'url' => ['products/catalog', 'topGrName' => $xleb[0], 'grName' => $xleb[1]]];
        $this->params['breadcrumbs'][] = ['label' => $xleb[5], 'url' => ['products/index', 'topGrName' => $xleb[0], 'grName' => $xleb[1], 'catName' => $xleb[2]]];
    }

}

$this->params['breadcrumbs'][] = $model->name;
?>

<div class="products-view">

    <h1><?= Html::encode($model->name) ?></h1>
    <div class="col-xs-6 col-sm-8 col-md-6">
        <?php
        //echo VarDumper::dump($xleb);
        //echo '<br><br>';
        //echo VarDumper::dump($this->params['breadcrumbs']);
        ?>

        <?php echo Html::a(Html::img('/images/'.$model->mainImg[0]->img,['class'=>"img-responsive"]), ['/images/'.$model->mainImg[0]->img], ['class'=>'imagesOfGoods', 'rel'=>'gallery1']);
        if($model->prodImgs[0]->img)
            echo Html::a(Html::img('/images/'.$model->prodImgs[0]->img,['style'=>"width: 80px; height: 80px;"]), ['/images/'.$model->prodImgs[0]->img], ['class'=>'imagesOfGoods', 'rel'=>'gallery1']);
        if($model->prodImgs[1]->img)
            echo Html::a(Html::img('/images/'.$model->prodImgs[1]->img,['style'=>"width: 80px; height: 80px;"]), ['/images/'.$model->prodImgs[1]->img], ['class'=>'imagesOfGoods', 'rel'=>'gallery1']);
        if($model->prodImgs[2]->img)
            echo Html::a(Html::img('/images/'.$model->prodImgs[2]->img,['style'=>"width: 80px; height: 80px;"]), ['/images/'.$model->prodImgs[2]->img], ['class'=>'imagesOfGoods', 'rel'=>'gallery1']);
        ?>
        
    </div>

    <div class="col-xs-6 col-sm-4 col-md-6">
        <?=Html::beginForm('/products/add-to-cart', 'get')?>
        <p><span class="orange">Артикул:</span> <?=$model->articul?></p>
        <p><span class="orange">Бренд:</span> <?=$model->brand_name?></p>
        <p><span class="orange">Цена:</span> <b><?=$model->price?></b></p>
        <div class="inputRow">
            <?=Html::hiddenInput('id', $model->id)?>
            <?php if(!Yii::$app->cart->hasPosition($model->id)):?>
            <p>количесвто уп. <?=Html::input('text', 'orderAmount['.$model->id.']', '1', ['style'=>'width: 48px; height: 26px;'])?></p>
            <?php else:?>
            <p>в корзине <?=Html::input('text', 'orderAmount['.$model->id.']', Yii::$app->cart->getPositionById($model->id)->Quantity, ['style'=>'width: 48px; height: 26px;'])?> уп.</p>
            <?php endif;?>
        </div>  
        <?php if(!Yii::$app->cart->hasPosition($model->id)):?>
        <p><?=Html::submitButton('В корзину', ['class'=>'btn btn-primary vkorzinuButton', 'role'=>'button'])?></p>
        <?php else:?>
        <?=Html::hiddenInput('update', true)?>
        <p><?=Html::submitButton('Обновить', ['class'=>'btn btn-success vkorzinuButton', 'role'=>'button'])?></p>
        <?php endif;?>
        
        <p><?php //Html::a('В корзину', Url::to(['products/add-to-cart', 'id' => $model->id]),['class'=>'btn btn-primary', 'role'=>'button'])?></p>
        <?= Html::endForm() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 description">
        <?=$model->dscr ?> <br>
    </div>
    <div class="clearfix"></div>
    <?php if($related != null):?>
        <div id="relatedSell" class="row">
            <p>С этим товаром покупают:</p>
            <?php foreach ($related as $item){ ?>
                <div class="col-xs-4 col-sm-3 col-md-3">
                    <p class="name"><?=$item->name.' '.$item->dscr?></p>
                    <?=Html::a(Html::img('/images/'.$item->mainImg[0]->img,['style'=>"width: 80px; height: 80px;"]), Url::to(['products/view', 'goodsName' => $item->articul.'_'.$item->id]))?>
                </div>
            <?php } ?>
        </div>
    <?php endif;?>
</div>
