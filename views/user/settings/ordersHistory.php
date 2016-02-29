<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ListView;

/*
 * @var yii\web\View $this
 */

$this->title = 'История заказов - Электро Центр';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
$('.orderBody').hide();
$('.orderHead').click(function(){
        $(this).next('.orderBody').slideToggle(400);
    })
", $this::POS_READY);
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<div class="userAccount-index">
    <div class="row">
        <div class="col-md-3 col-md-push-9">
            <?= $this->render('_menu') ?>
        </div>
        <div class="col-md-9 col-md-pull-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::encode($this->title) ?>
                </div>
                <table class="table">
                <tr><th>Номер заказа</th><th></th><th>Дата заказа</th><th></th><th></th><th>Сумма Заказа</th></tr>
                <?php echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    //'layout' => '{items}',
                    'itemView' => '_orderView',
                ]);?>
            </table>
                <div class="panel-body">
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
