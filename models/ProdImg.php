<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prod_img".
 *
 * @property string $id
 * @property string $prod_id
 * @property string $img
 * @property string $big_img
 * @property string $crop_img
 * @property integer $main
 *
 * @property Products $prod
 */
class ProdImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prod_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prod_id', 'main'], 'integer'],
            [['img', 'big_img', 'crop_img'], 'required'],
            [['img', 'big_img', 'crop_img'], 'string', 'max' => 50],
            [['prod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['prod_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prod_id' => 'Prod ID',
            'img' => 'Img',
            'big_img' => 'Big Img',
            'crop_img' => 'Crop Img',
            'main' => 'Main',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProd()
    {
        return $this->hasOne(Products::className(), ['id' => 'prod_id']);
    }
}
