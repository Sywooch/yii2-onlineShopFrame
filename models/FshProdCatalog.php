<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fsh_prod_catalog".
 *
 * @property string $prod_id
 * @property string $cat_id
 *
 * @property Products $prod
 * @property FshCatalog $cat
 */
class FshProdCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fsh_prod_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prod_id', 'cat_id'], 'required'],
            [['prod_id', 'cat_id'], 'integer'],
            [['prod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['prod_id' => 'id']],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => FshCatalog::className(), 'targetAttribute' => ['cat_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prod_id' => 'Prod ID',
            'cat_id' => 'Cat ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProd()
    {
        return $this->hasOne(Products::className(), ['id' => 'prod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(FshCatalog::className(), ['id' => 'cat_id']);
    }
}
