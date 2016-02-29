<?php

use yii\widgets\Breadcrumbs;


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

echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);

?>