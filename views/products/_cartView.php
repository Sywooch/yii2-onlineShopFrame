<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php isset($this->params['nmberVcart'])? $this->params['nmberVcart']=$this->params['nmberVcart'] : $this->params['nmberVcart']=1; 
        //'<tr><th>#</th><th>Фото</th><th>Название</th><th>Артикул</th><th>В упак.</th><th>Цена за упак.</th><th>Кол-во упак.</th><th>Стоимость</th></tr>';
?>
<tr>
    <td><?=$this->params['nmberVcart']?></td>
    <td><?=Html::img('/images/'.$model->mainImg[0]->img,['style'=>"width: 40px; height: 40 px;"])?></td>
    <td><?=Html::a($model->name.' '.$model->dscr, Url::to(['products/view', 'goodsName' => $model->articul.'_'.$model->id]))?></td>
    <td><?=$model->articul?></td>
    <td><?=$model->size?></td>
    <td><?=number_format($model->price, 2, '.', ' ')?></td>
    <td><?=Html::input('text', 'itemToUpdate['.$model->id.']', $model->getQuantity(), ['style'=>'width: 30px;'])?></td>
    <td><?=number_format($model->getQuantity()*$model->price, 2, '.', ' ').' руб.'?></td>
    <td><?=Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::to(['products/update-cart', 'del' => $model->id]))?></td>
    
    
</tr>

<?php $this->params['nmberVcart'] = ++$this->params['nmberVcart']; ?>

