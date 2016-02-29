<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
/* @var $dataProvider yii\data\ArrayDataProvider
 * @var $cartInstance yz\shoppingcart\ShoppingCart */
$this->title = 'Корзина';

$this->registerJs("
    if($('*').is('.has-error')){
        $('#bodyStep_1').hide();
        $('#headStep_1').css({'background-color':'#C6C6C6','color':'#626262','cursor':'pointer'}).html('назад в корзину');
        $('#headStep_1').click(function(){
            $('#orderStep_2').slideUp(400);
            setTimeout(function() {
                $('#bodyStep_1').slideDown();
                $('#headStep_1').css({'background-color':'#F5F5F5','color':'#000','cursor':'default'}).html('Ваш заказ');;
            },400);
            $('#goToStep_2').click(function(){
                $('#bodyStep_1').slideUp(400);
                $('#headStep_1').css({'background-color':'#C6C6C6','color':'#626262','cursor':'pointer'}).html('назад в корзину');
                setTimeout(function() {
                    $('#orderStep_2').slideDown();
                },400);
                return false;
            });
        });
    }else{
        $('#orderStep_2').hide();
        $('#goToStep_2').click(function(){
            $('#bodyStep_1').slideUp(400);
            $('#headStep_1').css({'background-color':'#C6C6C6','color':'#626262','cursor':'pointer'}).html('назад в корзину');
            setTimeout(function() {
                $('#orderStep_2').slideDown();
            },400);
            $('#headStep_1').click(function(){
                $('#orderStep_2').slideUp(400);
                setTimeout(function() {
                    $('#bodyStep_1').slideDown();
                    $('#headStep_1').css({'background-color':'#F5F5F5','color':'#000','cursor':'default'}).html('Ваш заказ');;
               },400);
             });
            return false;
        })
    }
    ", $this::POS_READY);
?>

<div id="orderStep_1" class="cart-index">
    <?= Html::beginForm('/products/update-cart', 'post')?>
    <div class="panel">
        <?php if (!empty($dataProvider->allModels)) : ?>
        <div id="headStep_1" class="panel-heading">Ваш заказ</div>
        <div id="bodyStep_1">
            <table class="table">
                <tr><th>#</th><th>Фото</th><th>Название</th><th>Артикул</th><th>В упак.</th><th>Цена за упак.</th><th>Кол-во упак.</th><th>Стоимость</th></tr>
                <?php echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '{items}',
                    'itemView' => '_cartView',
                ]);?>
            </table>
            <div class="panel-body">
                <div class="cartUpdButton">
                    <div class="pull-right">
                        <?=Html::submitButton('<span class="glyphicon glyphicon-refresh"></span>  обновить корзину', ['class'=>'btn btn-primary btn-sm', 'style'=>'color:#000;'])?><br>
                        <div class="btn btn-primary btn-sm"><?=Html::a('<span class="glyphicon glyphicon-remove"></span> очистить корзину', Url::to(['products/update-cart', 'del' => 'all']))?></div>
                    </div>                
                </div>
                <div class="clearfix"></div>
                <?php count($cartInstance->getPositions()) == 1 ? $positionText = 'позиция' : $positionText = 'позиций'; ?>
                <div class="col-sm-9 col-xs-9">
                    <p>Итог: <?=count($cartInstance->getPositions()).' '.$positionText?> на сумму <?=number_format($cartInstance->cost, 2, '.', ' ')?> руб.</p>
                </div>
                <div class="col-sm-3 col-xs-3">
                    <div id="goToStep_2" class="btn btn-success btn-sm pull-right"><?=Html::a('<span class="glyphicon glyphicon-ok"></span> Оформить заказ', Url::to(['products/confirm-cart']))?></div>
                </div>            
            </div>            
        </div>             
        <?php else : ?>
        <div class="panel-heading">Ваша корзина пуста</div>
        <?php endif;?>
    </div>
    <?= Html::endForm() ?>
</div>

<div id="orderStep_2" class="cart-index">
    
    <div class="panel">
        <div class="panel-heading">Заполните пожалуйста контактную форму</div>
        <div id="bodyStep_2">
            <div class="row">
                <?php $form = ActiveForm::begin([
                        'id' => 'order-form',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => false,
                        'validateOnSubmit' => false,
                        'validateOnType' => true,
                        'validateOnBlur' => true,
                ]); ?>
                
                <div class="col-xs-12 col-sm-6">          
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true, 'inputOptions' => ['value' => $user->email]]) ?>
                    
                    <?= $form->field($model, 'name', ['inputOptions' => ['value' => $user->profile->name]]) ?>

                    <?= $form->field($model, 'phone_number', ['inputOptions' => ['value' => $user->profile->phone_number]]) ?>
                </div>    
                
                <div class="col-xs-12 col-sm-6">
                    <?= $form->field($model, 'delivery_address', ['inputOptions' => ['value' => $user->profile->delivery_address]])->textArea(['rows' => 3]) ?>

                    <?= $form->field($model, 'comments')->textArea(['rows' => 2]) ?>
                </div>
                
                <div class="col-xs-12 col-sm-12">
                    <?= $form->field($model, 'generateAccount', ['enableAjaxValidation' => true])->checkbox() ?>

                    <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>
                
                    <div class="form-group pull-right">
                        <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Сделать заказ', ['class' => 'btn btn-success', 'name' => 'contact-button', 'style'=>'color:#000;']) ?>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>      
            </div> 
        </div>             
    </div>
    
</div>
