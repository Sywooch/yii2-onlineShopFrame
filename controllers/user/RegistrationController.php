<?php
/*
 * Изменения касаются только рендиринга вида. Вместо метода render использую renderPartial 
 * для того чтобы вид формы открывался чистым в fancybox iframe
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
use dektrium\user\models\ResendForm;
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
            return $this->renderPartial('/message', [
                'title'  => Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        return $this->renderPartial('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }
    
    /**
     * Displays page where user can create new account that will be connected to social account.
     *
     * @param string $code
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConnect($code)
    {
        $account = $this->finder->findAccount()->byCode($code)->one();

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException();
        }

        /** @var User $user */
        $user = Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'connect',
            'username' => $account->username,
            'email'    => $account->email,
        ]);

        if ($user->load(Yii::$app->request->post()) && $user->create()) {
            $account->connect($user);
            Yii::$app->user->login($user, $this->module->rememberFor);
            return $this->goBack();
        }

        return $this->renderPartial('connect', [
            'model'   => $user,
            'account' => $account,
        ]);
    }
    
    /**
     * Displays page where user can request new confirmation token. If resending was successful, displays message.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionResend()
    {
        if ($this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        /** @var ResendForm $model */
        $model = Yii::createObject(ResendForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->resend()) {
            return $this->renderPartial('/message', [
                'title'  => Yii::t('user', 'A new confirmation link has been sent'),
                'module' => $this->module,
            ]);
        }

        return $this->renderPartial('resend', [
            'model' => $model,
        ]);
    }
    
    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $user->attemptConfirmation($code);

        return $this->render('/messageNoBody', [
            'title'  => Yii::t('user', 'Account confirmation'),
            'module' => $this->module,
        ]);
    }
 
}
