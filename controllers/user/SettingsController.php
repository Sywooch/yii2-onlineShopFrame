<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\controllers\user;

use dektrium\user\controllers\SettingsController as BaseSettingsController;


use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Orders;
use yii\data\ArrayDataProvider;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsController extends BaseSettingsController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'confirm', 'networks', 'orders', 'disconnect'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionOrders()
    {
        $model = Orders::find()->where('user_id=:user_id', [':user_id'=>Yii::$app->user->identity->getId()])->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);    
        return $this->render('ordersHistory', [
            'dataProvider' => $dataProvider,
        ]);
    }

    

}
