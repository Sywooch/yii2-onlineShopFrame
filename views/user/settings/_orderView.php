<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php isset($this->params['numberOrder'])? $this->params['numberOrder']=$this->params['numberOrder'] : $this->params['numberOrder']=1; 
        //'<tr><th>#</th><th>Дата заказа</th><th>Сумма Заказа</th></tr>';
?>
<tr class="orderHead">
    <td colspan="2"><?=$model->id?></td>
    <td colspan="3"><?=$model->date?></td>
    <td><?=$model->order_sum?></td>
</tr>
<tr class="orderBody">
    <td colspan="6">
        <table class="orderBodyTable">
            <?php
            foreach ($model->products as $prod){
                // \yii\helpers\VarDumper::dump($prod->getOrdersProds($model->id)->one()->qty);
                echo '<tr>';
                echo '<td>&ensp;</td>';
                echo '<td>'.$prod->name.'</td>';
                echo '<td> арт. '.$prod->articul.'</td>';
                echo '<td>'.$prod->getOrdersProds($model->id)->one()->qty.' уп.</td>';
                echo '<td style="text-align: right;">'.$prod->price*$prod->getOrdersProds($model->id)->one()->qty.' руб.</td>';
                echo '</tr>';
            }
            ?>
        </table>

    </td>
</tr>



<?php $this->params['numberOrder'] = ++$this->params['numberOrder']; ?>

