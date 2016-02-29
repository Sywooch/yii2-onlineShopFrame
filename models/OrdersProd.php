<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_prod".
 *
 * @property string $order_id
 * @property string $prod_id
 * @property string $qty
 *
 * @property Orders $order
 * @property Products $prod
 */
class OrdersProd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_prod';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'prod_id', 'qty'], 'required'],
            [['order_id', 'prod_id', 'qty'], 'integer'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['prod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['prod_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'prod_id' => 'Prod ID',
            'qty' => 'Qty',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProd()
    {
        return $this->hasOne(Products::className(), ['id' => 'prod_id']);
    }
}
