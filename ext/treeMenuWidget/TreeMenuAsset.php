<?php
/**
 * Created by PhpStorm.
 * User: KVUSH-NBOOK
 * Date: 14.11.2015
 * Time: 13:23
 */

namespace app\ext\treeMenuWidget;


use yii\web\AssetBundle;

class TreeMenuAsset extends AssetBundle
{
    public $sourcePath = '@app/ext/treeMenuWidget';
    public $js = [
        'js/jQuery.tree.js',
        //'js/setup.js',
    ];
    public $css = [
        'css/enhanced.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}