<?php
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\VarDumper;

/** @var app\models\Products $model */

?>
<div id="ajaxLoad_<?=$model->id?>"></div>
<div class="col-lg-4 col-md-6 col-sm-6">
    <?php
    //echo varDumper::dump();
    ?>
    <div class="thumbnail catalogItemView">
        <div class="image-block" style="background-image: url(<?='/images/'.$model->mainImg[0]->img?>); background-position: center center; background-size: cover; "><?= Html::a('', Url::to(['products/view', 'goodsName' => $model->articul.'_'.$model->id]), ['class'=>'openGoodsAjax', 'data-pjax' =>0]) ?></div>
        <div class="caption">
            <h2><?= Html::a($model->name.' '.$model->dscr, Url::to(['products/view', 'goodsName' => $model->articul.'_'.$model->id]), ['class'=>'openGoodsAjax', 'data-pjax' =>0]) ?></h2>
            <p class="dscr">
                бренд: <?=$model->brand_name?><br>
                артикул: <?=$model->articul?><br>
            </p>
            <p class="price pull-right">
                <?=number_format($model->price, 2, '.', ' ');?> &#8381;/уп.
            </p>
            <div class="clearfix"></div>
            
            <?=Html::beginForm('/products/add-to-cart', 'get', ['data-pjax' =>0,])?>
            <div class="inputRow pull-left">
                <?=Html::hiddenInput('id', $model->id)?>
                <?php if(!Yii::$app->cart->hasPosition($model->id)):?>
                <p>кол-во уп. <?=Html::input('text', 'orderAmount['.$model->id.']', '1', ['style'=>'width: 35px; height: 30px;'])?></p>
                <?php else:?>
                <p>в корзине <?=Html::input('text', 'orderAmount['.$model->id.']', Yii::$app->cart->getPositionById($model->id)->Quantity, ['style'=>'width: 35px; height: 30px;'])?> уп.</p>
                <?php endif;?>
            </div>            
            <?php if(!Yii::$app->cart->hasPosition($model->id)):?>
            <p class="pull-right">
                <?=Html::hiddenInput('update', 0)?>
                <?=Html::submitButton('В корзину', ['class'=>'btn btn-primary vkorzinuButton', 'role'=>'button'])?>
            </p>
            <?php else:?>
            <p class="pull-right">
                <?=Html::hiddenInput('update', true)?>
                <?=Html::submitButton('Обновить', ['class'=>'btn btn-success vkorzinuButton', 'role'=>'button'])?>
            </p>
            <?php endif;?>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>