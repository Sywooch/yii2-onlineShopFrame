<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

/**
 * This is the model class for table "products".
 *
 * @property string $id
 * @property string $name
 * @property string $brand_name
 * @property string $articul
 * @property string $price
 * @property string $size
 * @property string $dscr
 * @property string $tags
 * @property integer $best_sell
 * @property string $related_product
 * @property string $created_at
 * @property string $updated_at
 * @property string $look_counter
 * @property integer $show_flag
 *
 * @property FshProdCatalog[] $fshProdCatalogs
 * @property OrdersProd[] $ordersProds
 * @property ProdImg[] $prodImgs
 */
class Products extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'dscr'], 'required'],
            [['price'], 'number'],
            [['dscr'], 'string'],
            [['best_sell', 'show_flag'], 'integer'],
            [['name', 'brand_name', 'size', 'tags'], 'string', 'max' => 255],
            [['articul', 'related_product'], 'string', 'max' => 50],
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
            'brand_name' => 'Brand Name',
            'articul' => 'Articul',
            'price' => 'Price',
            'size' => 'Size',
            'dscr' => 'Dscr',
            'tags' => 'Tags',
            'best_sell' => 'Best Sell',
            'related_product' => 'Related Product',
            'show_flag' => 'Show Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFshProdCatalogs()
    {
        return $this->hasMany(FshProdCatalog::className(), ['prod_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersProds($order_id)
    {
        return $this->hasMany(OrdersProd::className(), ['prod_id' => 'id'])->where('order_id = :order_id', [':order_id' => $order_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdImgs()
    {
        return $this->hasMany(ProdImg::className(), ['prod_id' => 'id'])->where(['main' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainImg()
    {
        return $this->hasMany(ProdImg::className(), ['prod_id' => 'id'])->where(['main' => 1]);
    }


    public static function takeGoods($id)
    {
            $goods = FshCatalog::findOne($id)->getProds();

        //return VarDumper::dump($goods).'MODELS';

        $dataProvider = new ActiveDataProvider([
            'query' => $goods,
            'pagination' => false,
        ]);
        return 	$dataProvider;
    }


    public static function takeAllGoods($IDs)
    {
        $badArray = [];
        $goods = [];
        for($i = 0, $size = count($IDs); $i < $size; ++$i) {
            $badArray[] = FshCatalog::findOne($IDs[$i]['id'])->getProds()->all();
        }

        foreach ($badArray as $k1=>$goodArray){
            foreach ($goodArray as $k2=>$good){
                $goods[]=$good;
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $goods,
            'pagination' => false,
        ]);
        //echo VarDumper::dump($dataProvider);
        return 	$dataProvider;
    }


    public static function takeNewGoods()
    {
        $goods = self::find()->where('updated_at >= :time', [':time' => time()-(7*24*60*60)]);
        $dataProvider = new ActiveDataProvider([
            'query' => $goods,
            'pagination' => false,
        ]);
        return 	$dataProvider;
    }
}
