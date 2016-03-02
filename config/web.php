<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'Basic',
    'name'=>'"App name"',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout' =>'column2',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

                'site/<_a:[\w\-]+>' => '/site/<_a>',
                '<_c:products>/<_a:add-to-cart>' => '/products/add-to-cart',
                '<_c:products>/<_a:cart>' => '/products/cart',
                '<_c:products>/<_a:update-cart>' => '/products/update-cart',
                '<_c:order>/<_a:make>' => '/order/make',

                '<_m:user>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '/user/<_c>/<_a>',

                'catalog/<topGrName:[\w+\-\~]+>/<grName:[\w+\-\~]+>' => '/products/catalog',
                
                '<topGrName:[\w+\-\~]+>/<grName:[\w+\-\~]+>/<catName:[\w+\-\~]+>' => '/products/index',
                '<grName:[\w+\-\~]+>/<catName:[\w+\-\~]+>' => '/products/index',
                '<catName:[\w+\-\~]+>' => '/products/index',

                [
                    'pattern' => 'view/<goodsName:[\w+\-\~]+>',
                    'route' => '/products/view',
                    'suffix' => '.html',
                ],

                //'<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                //'<_c:[\w\-]+>' => '<_c>/index',
                //'<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
            ],
        ],

        'JQTreeMenu' => [
            'class'=>'app\ext\treeMenuWidget\TreeMenu',
        ],

        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
            'cartId' => 'app_cart',
        ],

        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '',
            'secret' => '',
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'layout' => '@app/views/layouts/main',
            'modelMap' => [
                'Profile' => 'app\models\user\Profile',
                'User' => 'app\models\user\User',
            ],
            'controllerMap' => [
                //'security' => 'app\controllers\user\SecurityController',
                'registration' => 'app\controllers\user\RegistrationController',
                //'recovery' => 'app\controllers\user\RecoveryController',
                'settings' => 'app\controllers\user\SettingsController',
            ],
            'mailer' => [
                'sender'                => ['info@example.com' => 'info'], // or ['no-reply@myhost.com' => 'Sender name']
                'welcomeSubject'        => 'welcome',
                'confirmationSubject'   => 'confirm',
                'reconfirmationSubject' => 'reconfirm',
                'recoverySubject'       => 'recovery',
            ],
        ],
    ],
    'params' => $params,
    'language' => 'en-EN',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
