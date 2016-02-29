<?php

namespace app\models;

use Yii;
use yii\base\Model;
use dektrium\user\models\User;

/**
 * ContactForm is the model behind the contact form.
 */
class OrderForm extends Model
{
    public $name;
    public $phone_number;
    public $email;
    public $comments;
    public $generateAccount=true;
    public $reCaptcha;
    public $delivery_address;
    
    public function __construct()
    {
        parent::__construct();
        $this->generateAccount = Yii::$app->user->isGuest ? true : false;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'checkExistsEmail'],
            ['phone_number', 'string'],
            ['generateAccount', 'boolean'],
            // verifyCode needs to be entered correctly
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6Lc5fRkTAAAAAMbnliPZZFxHo8cjLEycR8HuJw2d']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя, Фамилия',
            'email' => 'e-mail',
            'phone_number' => 'контактый номер телефона',
            'delivery_address' => 'адрес доставки',
            'comments' => 'коментарии к заказу',
            'reCaptcha'=>'Анти спам проверка',
            'generateAccount'=>'Создать автоматически личный кабинет?',
        ];
    }

    public function checkExistsEmail()
    {
        if ($this->generateAccount && User::find()->where('email=:email', [':email'=>$this->email])->exists()){
            $this->addError('email','Этот e-mail уже зарегистрирован. Для продолжения оформления заказа пожалуйста авторизируйтесь или отключите опцию автоматического создания личного кабинета.');
        }
    }
  
}
