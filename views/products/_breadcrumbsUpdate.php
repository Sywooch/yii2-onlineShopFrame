<?php

use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categoryName string category name */
/* @var $groupName string group name */
/* @var $topGroupName string topGroup name */

$topGrSlug = Yii::$app->request->queryParams['topGrName'];
$grSlug = Yii::$app->request->queryParams['grName'];
$catSlug = Yii::$app->request->queryParams['catName'];

Yii::$app->session->set('forBreadCrumbs', [$topGrSlug, $grSlug, $catSlug, $topGroupName, $groupName, $categoryName]);

$this->title = $categoryName.' - '.Yii::$app->name; // для случая когда не один из if не подойдет т.е. для случая просмотра топ группы
/*
 * Перемещение по хлебным крошкам
 */
if ($topGroupName != null AND $groupName != null AND $categoryName == null){ // случай просмотра подгруппы
    $this->params['breadcrumbs'][] = ['label' => $topGroupName, 'url' => ['products/catalog', 'topGrName' => $topGrSlug, 'grName' => '0']];
    $this->params['breadcrumbs'][] = $groupName;
    $this->title = $groupName.' - catalog - '.Yii::$app->name;
}
if ($topGroupName != null AND $groupName == null AND $categoryName == null){ // случай просмотра топ группы
    $this->params['breadcrumbs'][] = $topGroupName;
    $this->title = $topGroupName.' - catalog - '.Yii::$app->name;
}
if ($topGroupName==null AND $groupName!=null AND $categoryName==null){ // случай просомтра группы с 1уровневой вложеностью
    $this->params['breadcrumbs'][] = $groupName;
    $this->title = $groupName.' - catalog - '.Yii::$app->name;
}
/*
if ($topGroupName!=null AND $groupName!=null AND $categoryName!=null){   // случай просмотра категории
    $this->params['breadcrumbs'][] = ['label' => $topGroupName, 'url' => ['products/catalog', 'topGrName' => $topGrSlug]];
    $this->params['breadcrumbs'][] = ['label' => $groupName, 'url' => ['products/catalog', 'topGrName' => $topGrSlug, 'grName' => $grSlug]];
    $this->title = $categoryName;
}
if ($topGroupName==null AND $groupName!=null AND $categoryName!=null){ // случай просомтра категории группы с 1уровневой вложеностью
    $this->params['breadcrumbs'][] = ['label' => $groupName, 'url' => ['products/catalog', 'topGrName' => '0', 'grName' => $grSlug]];
    $this->title = $categoryName;
}*/
/*
 * Перемещение по меню каталога
 */
if ($topGroupName == null AND $groupName == null AND $categoryName != null){ // случай просомтра категорий без групп

    $this->params['breadcrumbs'][] = $categoryName;
}
if ($topGroupName == null AND $groupName !=  null AND $categoryName != null){ // случай просомтра категорий в группе

    $this->params['breadcrumbs'][] = ['label' => $groupName, 'url' => ['products/catalog', 'topGrName' => '0', 'grName' => $grSlug]];
    $this->params['breadcrumbs'][] = $categoryName;
}
if ($topGroupName !=  null AND $groupName !=  null AND $categoryName != null){ // случай просомтра категорий в топ группе

    $this->params['breadcrumbs'][] = ['label' => $topGroupName, 'url' => ['products/catalog', 'topGrName' => $topGrSlug, 'grName' => '0']];
    $this->params['breadcrumbs'][] = ['label' => $groupName, 'url' => ['products/catalog', 'topGrName' => $topGrSlug, 'grName' => $grSlug]];
    $this->params['breadcrumbs'][] = $categoryName;
}

echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);

?>