<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use app\models\Orders;
use app\models\OrdersProd;

/**
 * Description of OrderController
 *
 * @author KVUSH-NBOOK
 */
class OrderController extends Controller
{
    
    public function actionMake(){
        $newOrder = new Orders;
        if(Yii::$app->user->isGuest){
            $newOrder->user_id = Yii::$app->session->get('session_user_id') ? Yii::$app->session->get('session_user_id') : 0;
            Yii::$app->session->remove('session_user_id');
        }else{
            $newOrder->user_id = Yii::$app->user->id;
        }        
        $newOrder->date = date('Y:n:d, G:i');
        $newOrder->order_sum = Yii::$app->cart->cost;
	    $newOrder->save();
        $currentCart = Yii::$app->cart->getPositions();
        //echo \yii\helpers\VarDumper::dump($currentCart);
        foreach ($currentCart as $i){
            $OrderProd = new OrdersProd;
            $OrderProd->order_id=$newOrder->id;
            $OrderProd->prod_id=$i->id;
            $OrderProd->qty=$i->quantity;
            $OrderProd->save();
	    }
        $messages = [];

        $messages[] = Yii::$app->mailer->compose('orderToCustomer', ['cartInstance' => Yii::$app->cart, 'orderID' => $newOrder->id])
            ->setFrom([Yii::$app->params['SellManagerEmail'] => 'Sell Manager'])
            ->setTo(Yii::$app->session->get('OrderForm')['email'])
            ->setSubject('Your order');
        $messages[] = Yii::$app->mailer->compose('orderToManager', ['cartInstance' => Yii::$app->cart, 'orderID' => $newOrder->id])
            ->setFrom('order@example.com')
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('New order');

        Yii::$app->mailer->sendMultiple($messages);

        Yii::$app->session->setFlash('newOrder','Ваш заказ принят в обработку. Мы отправили Вам письмо с информацией по вашему заказау. В ближайшее время наш менеджер свяжется с Вами.');
        Yii::$app->cart->removeAll();
        Yii::$app->session->remove('OrderForm');
        $this->goHome();
    }
}
