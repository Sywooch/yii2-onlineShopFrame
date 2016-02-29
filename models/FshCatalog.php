<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fsh_catalog".
 *
 * @property string $id
 * @property string $name
 * @property string $group_id
 * @property integer $sort
 * @property string $slug
 *
 * @property FshProdCatalog[] $fshProdCatalogs
 */
class FshCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fsh_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['group_id'], 'string'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['slug'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'group_id' => 'Group ID',
            'sort' => 'Sort',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFshProdCatalogs()
    {
        return $this->hasMany(FshProdCatalog::className(), ['cat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProds()
    {
        return $this->hasMany(Products::className(), ['id' => 'prod_id'])->viaTable('Fsh_prod_catalog', ['cat_id' => 'id'])
            ->where(['show_flag' => 1]);
    }
}
