<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string  $name
 * @property string  $phone_number
 * @property string  $delivery_address
 * @property string  $company
 * @property User    $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends ActiveRecord
{
    /** @var \dektrium\user\Module */
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->module = Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string'],
            ['delivery_address', 'string'],
            ['phone_number', 'string'],
            ['company', 'string'],
            //'bioString' => ['bio', 'string'],
            //'publicEmailPattern' => ['public_email', 'email'],
            //'gravatarEmailPattern' => ['gravatar_email', 'email'],
            //'websiteUrl' => ['website', 'url'],
            //'nameLength' => ['name', 'string', 'max' => 255],
            //'publicEmailLength' => ['public_email', 'string', 'max' => 255],
            //'gravatarEmailLength' => ['gravatar_email', 'string', 'max' => 255],
            //'locationLength' => ['location', 'string', 'max' => 255],
            //'websiteLength' => ['website', 'string', 'max' => 255],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name'            => 'Имя, Фамилия',
            'phone_number'    => 'Контактный телефон',
            'delivery_address'=> 'Адрес доставки',
            'company'         => 'Компания',
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }
}
