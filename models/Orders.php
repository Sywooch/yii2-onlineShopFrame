<?php

namespace app\models;

use dektrium\user\models\User;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $date
 * @property string $order_sum
 * @property string $status_id
 *
 * @property User $user
 * @property OrderStatus $status
 * @property OrdersProd[] $ordersProds
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date', 'order_sum'], 'required'],
            [['user_id', 'status_id'], 'integer'],
            [['date'], 'safe'],
            [['order_sum'], 'number'],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            //[['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'order_sum' => 'Order Sum',
            'status_id' => 'Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersProds()
    {
        return $this->hasMany(OrdersProd::className(), ['order_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'prod_id'])
            ->viaTable('orders_prod', ['order_id' => 'id']);
    }
}
