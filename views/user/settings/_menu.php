<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\widgets\Menu;
use yii\helpers\Url;
use yii\helpers\Html;

$dirh = opendir($dirname);
if ($dirh) {
    while (($dirElement = readdir($dirh)) !== false) {
        
    }
    closedir($dirh);
}

/** @var dektrium\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

?>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account']],
                ['label' => Yii::t('user', 'Networks'), 'url' => ['/user/settings/networks'], 'visible' => $networksVisible],
                ['label' => 'ใบสั่ง', 'url' => ['/user/settings/orders']],
                
                [
                    'label' => 'ออกจากระบบ',
                    'url' => ['/user/security/logout'],
                    'template' => '<a href="{url}" data-method="post">{label}</a>',
                ],
            ],
        ]) ?>
    </div>
</div>
