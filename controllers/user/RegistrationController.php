<?php
/*
 * Допил метода регистрации.
 * Добавил авто регистрацию при оформлении заказа
 */

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\controllers\user;

use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
use Yii;
use dektrium\user\models\RegistrationForm;
use dektrium\user\models\User;
use yii\web\NotFoundHttpException;


/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 * 
 */
class RegistrationController extends BaseRegistrationController
{
   // public $layout = 'main';

    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionRegister($auto=0)
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        /** @var RegistrationForm $model */
        $model = Yii::createObject(RegistrationForm::className());

        $this->performAjaxValidation($model);
        
        /* Мой допил для автоматической регистрации
         * Попадаем сюда при оформление заказа если стоит галочка в форме заказа
         */
        if($auto==1){
            /*
             * Две функции для проверки мыла и имени. 
             * Проверка мыла по идее тут не нужна, потому что сюда попадаем только в случае валидного мыла из формы оформления заказа
             * но на всякий пожарный случай проверяем мыло еще раз. 
             * Проврека имени необходима, так как име генерируется из адреса почты - берем строку до собачки. 
             * Так как разная почта может содержать одинаковое начало, то может быть случай что имя сгенерируется одинаковым
             */
            function chekEmail($givenEmail){
                return User::find()->where('email=:email', [':email'=>$givenEmail])->exists();
            }
            function chekUserName($givenUserName){
                return User::find()->where('username=:username', [':username'=>$givenUserName])->exists();
            }
            
            /*
             * Тут если мыло не прошло проверку сразу отправляем обратно.
             */
            if(chekEmail(Yii::$app->session->get('OrderForm')['email'])){
                $this->goBack(); 
            }
            
            Yii::$app->getModule('user')->enableGeneratingPassword = true;  // Ставим генерацию пароля в тру
            
            /*
             * Заполняем нашу модель RegistrationForm 
             * - присваеваем атрибуту email значение из соответсвующего значения формы оформления заказа
             * переданного через сессию
             */
            $model->email=Yii::$app->session->get('OrderForm')['email'];  
            
            /*
             * Тут генерируем имя из адреса почты. Все то что до @ будет именем
             * Если имя получится меньше 3 символов то дописываем его "_" с обоих сторон
             */
            $re = "/[\\w+\\-\\~\\.]+/"; 
            preg_match($re, $model->email, $matches);
            $model->username = $matches[0];
            if(strlen($model->username)<3){
                $model->username='_'.$model->username.'_';
            }
            /*
             * Проверяем имя на уникальность
             * если что то дописываем номер в конце
             */           
            if (chekUserName($model->username)){
                $k=1;
                do {                    
                    $newUserName=$model->username.'_'.$k;
                    $k++;
                }while(chekUserName($newUserName));
                
                $model->username = $newUserName;
            }
            /*
             * В конце регистрируем модель
             * сохраняем данные из формы заказа профиль
             * и перенаправляемся на экшен маке ордер.
             */
            $model->register();
            Yii::$app->session->removeFlash('info');
            $profile=User::find()->where('email=:email', [':email'=>$model->email])->one()->profile;
            //echo \yii\helpers\VarDumper::dump($profile->name);
            $profile->name=Yii::$app->session->get('OrderForm')['name'];
            $profile->phone_number=Yii::$app->session->get('OrderForm')['phone_number'];
            $profile->delivery_address=Yii::$app->session->get('OrderForm')['delivery_address'];
            $profile->save();
            return $this->redirect(['/order/make']);       
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->render('/message', [
                'title'  => Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    } 
}
