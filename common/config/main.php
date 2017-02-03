<?php
return [
    'name' => 'We Track',
    'timeZone' => 'Asia/Singapore',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=we_track',
            'username' => 'worker',
            'password' => 'abcd1234',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '913410235019-te7nksk88e7niln6e8noc8bfe935d9bk.apps.googleusercontent.com',
                    'clientSecret' => '2_B-eJ_mkU626RdI_VOQyyPk',
                ],
            ],
        ]
    ],
];
