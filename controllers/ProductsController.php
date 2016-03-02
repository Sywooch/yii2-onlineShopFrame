<?php

namespace app\controllers;

use app\models\FshCatalog;
use app\models\FshGroupofcat;
use app\models\FshGroupofgroups;
use Yii;
use app\models\Products;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
//use yii\sphinx\Query;  
use app\models\OrderForm;
use dektrium\user\models\User;
use yii\web\Response;
use yii\bootstrap\ActiveForm;

use yii\helpers\VarDumper;


/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Products models in category.
     * @return mixed
     */
    public function actionIndex($catName, $grName=null, $topGrName=null, $breadcrumbs=null)
    {
        //$grName=preg_replace('/^~/', '', $grName, 1);
        $id = FshCatalog::find()->select('id')->where('slug=:catName', [':catName' => $catName])->one()->id;
        $categoryName = FshCatalog::findOne($id)->name;
        $topGroupName = FshGroupofgroups::find()->where('slug=:topGrName', [':topGrName'=>$topGrName])->one()->name;
        $groupName = FshGroupofcat::find()->where('slug=:grName', [':grName'=>$grName])->one()->name;

        $dataProvider = Products::takeGoods($id);

        if (Yii::$app->request->isAjax and $breadcrumbs==null){
            return $this->renderPartial('index', [
                'dataProvider' => $dataProvider,
                'categoryName' => $categoryName,
                'groupName' => $groupName,
                'topGroupName' => $topGroupName,
            ]);
        }

        if (Yii::$app->request->isAjax and $breadcrumbs==1){
            return $this->renderPartial('_breadcrumbsUpdate', [
                'dataProvider' => $dataProvider,
                'categoryName' => $categoryName,
                'groupName' => $groupName,
                'topGroupName' => $topGroupName,
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categoryName' => $categoryName,
            'groupName' => $groupName,
            'topGroupName' => $topGroupName,
        ]);
    }


    /**
     * Lists all Products models in group.
     * @return mixed
     */

    public function actionCatalog($topGrName='all', $grName='0'/*, $getFolder=0*/)
    {
        $topGrName == 'all' ? $topGrId = 1 : $topGrId = FshGroupofgroups::find()->select('id')->where('slug=:topGrName', [':topGrName'=>$topGrName])->one()->id;
        $grIDs = FshGroupofcat::find()->select('id')->where('FIND_IN_SET(:id, topgroup_id)',[':id'=>$topGrId])->all();
        $IDs = [];
        foreach($grIDs as $grID){
            $stepForIDs = FshCatalog::find()->select('id')->where('FIND_IN_SET(:id, group_id)',[':id'=>$grID['id']])->all();
            $IDs =  array_merge ($IDs, $stepForIDs);
        }
        $groupName = null;
        if($grName!='0'){
            //$grName=preg_replace('/^~/', '', $grName, 1);
            $grId = FshGroupofcat::find()->select('id')->where('slug=:grName', [':grName'=>$grName])->one()->id;
            $IDs = FshCatalog::find()->select('id')->where('FIND_IN_SET(:id, group_id)',[':id'=>$grId])->all();
            $groupName = FshGroupofcat::findOne($grId)->name;
        }

        //VarDumper::dump($IDs);

        $topGroupName = FshGroupofgroups::findOne($topGrId)->name;

       /* if (Yii::$app->request->isAjax AND $getFolder==1){
            if($grName!='0'){
                echo '#group'.$grId;
            }else{
                echo '#topGroup'.$topGrId;
            }
            return false;
        }*/

        $dataProvider = Products::takeAllGoods($IDs);
        //\yii\helpers\VarDumper::dump($dataProvider);

       /* if (Yii::$app->request->isAjax AND $getFolder==0){
            return $this->renderPartial('index', [
                'dataProvider' => $dataProvider,
                'topGroupName' => $topGroupName,
                'groupName' => $groupName,
            ]);
        }*/
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'topGroupName' => $topGroupName,
            'groupName' => $groupName,
        ]);
    }

     /**
     * Displays a single Products model.
     * @param string $goodsName
     * @return mixed
     */
    public function actionView($goodsName, $breadcrumbs=null)
    {
        /*$this->on(
            $this::EVENT_AFTER_ACTION,
            function () {
                Yii::$app->session->remove('forBreadCrumbs');
            }
        );*/

        preg_match('/(?<=_)\d+$/', $goodsName, $matches);
        $id=$matches[0];
        $relatedProd=explode(',',$this->findModel($id)->related_product);

        // VarDumper::dump($this->findModel($id)->related_product);
        if (Yii::$app->request->isAjax and $breadcrumbs==null){
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'related' => $this->findRelatedModels($relatedProd)
            ]);
        }

        if (Yii::$app->request->isAjax and $breadcrumbs==1){
            return $this->renderAjax('_breadcrumbsViewUpdate', [
                'model' => $this->findModel($id),
                'related' => $this->findRelatedModels($relatedProd)
            ]);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'related' => $this->findRelatedModels($relatedProd)
        ]);
    }

    /**
     * Work with shopping cart - add to cart
     */
    public function actionAddToCart($id, $update=false)
    {
        $cart = Yii::$app->cart;
        $model = Products::findOne($id);
        /*Если menuUpdtade = 0 то это случай для обновления ячейки витрины
         * Ниже код не исполнится, и повторно в корзину ничего не добавится.
         * этот случай возникает уже после menuUdpade = 1
        */
        if (Yii::$app->request->isAjax && $_GET['menuUpdate'] == 0){
            return $this->renderPartial('_catalogView', ['model' => $model]);
        }else{
            if ($model){
                isset($_GET['orderAmount']) ? $qty=$_GET['orderAmount'][$id] : $qty=1;
                if ($update==false){
                    $cart->put($model, $qty);
                }elseif ($update==true){
                    $cart->update($model, $qty);
                }

                if (Yii::$app->request->isAjax && $_GET['menuUpdate'] == 1){
                    echo 'Товаров: '.count($cart->getPositions()).'<br>Cумма: '.number_format($cart->cost, 2, '.', ' ').' руб.';
                    return false;
                }
                return $this->redirect($_SERVER['HTTP_REFERER']);
            }
            throw new NotFoundHttpException();
        }
        //echo \yii\helpers\VarDumper::dump(Yii::$app->request->isAjax);
    }

    /**
     * Work with shopping cart - Update cart
     */
    public function actionUpdateCart($del='')
    {
        $cartArr = Yii::$app->cart->getPositions();
        if ($del != '' && $del != 'all'){
            Yii::$app->cart->removeById($del);
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }elseif ($del == 'all') {
            Yii::$app->cart->removeAll();
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        if (isset($_POST['itemToUpdate'])){
            foreach ($cartArr as $i){
                Yii::$app->cart->update($i, $_POST['itemToUpdate'][$i->id]);
            }
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Work with shopping cart - view cart
     */
    public function actionCart()
    {
        $cart = Yii::$app->cart;
        $model = new OrderForm();
        Yii::$app->user->isGuest ? $user = null : $user = User::findOne(Yii::$app->user->id);

        //\yii\helpers\VarDumper::dump($cart->cost);

        // нужно для проверки поля с e-mail на существование адресса эл.почты в таблице user базы данных.
        if (Yii::$app->request->isAjax) {
            $model->email = Yii::$app->request->post('OrderForm')['email'];
            $model->generateAccount = Yii::$app->request->post('OrderForm')['generateAccount'];
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model, 'email');
        }
        //\yii\helpers\VarDumper::dump(Yii::$app->request->post('OrderForm'));
        if (Yii::$app->request->post('OrderForm') != null){
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                Yii::$app->session->set('OrderForm', Yii::$app->request->post('OrderForm'));
                if (Yii::$app->request->post('OrderForm')['generateAccount']==1) {
                    return $this->redirect(['user/register', 'auto'=>'1']);
                }elseif (Yii::$app->request->post('OrderForm')['generateAccount']==0){
                    return $this->redirect(['/order/make']);
                }
            };
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $cart->positions,
            'pagination' => false,
        ]);

        return $this->render('cart', [
            'dataProvider' => $dataProvider,
            'cartInstance' => $cart,
            'model' => $model,
            'user' => $user,
        ]);

    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findRelatedModels($ids){
        return Products::findAll($ids);
    }
}
