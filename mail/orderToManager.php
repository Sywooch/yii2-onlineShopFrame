<?php
/**
 * Created by PhpStorm.
 * User: KVUSH-NBOOK
 * Date: 08.02.2016
 * Time: 22:00
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $dataProvider yii\data\ArrayDataProvider
 * @var $cartInstance yz\shoppingcart\ShoppingCart */
?>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= 'Поступил новый заказ №'.$orderID.' от клиента'?><br>
    Имя: <?=Yii::$app->session->get('OrderForm')['name']?><br>
    Телефон: <?=Yii::$app->session->get('OrderForm')['phone_number']?><br>
    e-mail: <?=Yii::$app->session->get('OrderForm')['email']?><br>
    <br>
    Адресс доставки: <?=Yii::$app->session->get('OrderForm')['delivery_address']?><br>
</p>

<table>
    <tr style="background: #C6C6C6;"><th>#</th><th>Фото</th><th>Название</th><th>Артикул</th><th>В упак.</th><th>Цена за упак.</th><th>Кол-во упак.</th><th>Стоимость</th></tr>
    <?php $number=1;?>
    <?php foreach ($cartInstance->getPositions() as $item){?>
        <?php $file='../web/images/'.$item->mainImg[0]->img;?>
    <tr>
        <td><?= $number ?></td>
        <?php if(file_exists($file)):?>
            <td><?= Html::img($message->embed(Url::to(['/images/'.$item->mainImg[0]->img], true)),['style'=>"width: 50px; height: 50 px;"]) ?></td>
            <?php else: ?>
            <td></td>
        <?php endif;?>
        <td><?=Html::a($item->name.' '.$item->dscr, Url::to(['products/view', 'goodsName' => $item->articul.'_'.$item->id], true))?></td>
        <td><?= $item->articul ?></td>
        <td><?= $item->size ?></td>
        <td><?= number_format($item->price, 2, '.', ' ') ?></td>
        <td><?= ($item->getQuantity()) ?></td>
        <td><?= number_format($item->getQuantity()*$item->price, 2, '.', ' ').' руб.'?></td>
    </tr>
    <?php $number++;?>
    <?php } ?>
</table>
<?php count($cartInstance->getPositions()) == 1 ? $positionText = 'позиция' : $positionText = 'позиций'; ?>
<div class="col-sm-9 col-xs-9">
    <p>Итог: <?=count($cartInstance->getPositions()).' '.$positionText?> на сумму <?=number_format($cartInstance->cost, 2, '.', ' ')?> руб.</p>
</div>

<p>Коментарий к заказу: <?=Yii::$app->session->get('OrderForm')['comments']?></p><br>

<p>Время заказа: <?=date("d/m/Y G:i")?></p>