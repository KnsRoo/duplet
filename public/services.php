<?php

use Websm\Framework\Di;
use Websm\Framework\Di\RawArgument;

use Front\Layout\Layout;
use Front\Pages\PagesSitemapGenerator;
use Websm\Framework\Sitemap\Service as Sitemap;

$di = Di\Container::instance();

$di->addShared('layout', Layout::class);

$di->addShared('sms-gate', new \Websm\Framework\Sms\SmsSenderClient(
    "http://smssender.websm.io:7000"
));
$di->addShared('assetsLoader', new \Websm\Framework\AssetsLoader(__DIR__ . '/assets/assets.json'));
$di->addShared('session', '\Websm\Framework\Session\SessionStorage');
$di->addShared('cart', '\Websm\Framework\Cart\SessionPool');
$di->addShared('nosql', '\Websm\Framework\NoSQL\RedisConnection');
$di->addShared('user', '\Websm\Framework\UserSession\User');
/* $di->addShared('locker', '\Websm\Framework\Locker\Service'); */
$di->addShared('crypt', new Websm\Framework\Crypt('8CcN2uvREX6GoengEncQzI6g1bAtchr3sHdD0Rsv'));

$di->addShared('sitemap', function () {

    $sitemap = new Sitemap;
    $pagesGenerator = new PagesSitemapGenerator();

    $sitemap->addGenerator($pagesGenerator);

    return $sitemap;

})
    ->setReturnType(Sitemap::class);

$di->addShared('feedback-api', function ($di) {

    return new API\Feedback\V1\Controller([
        /* 'verifyRecaptcha' => false, */
        'validationSchema' => [
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                    'pattern' => '.{1,150}$'
                ],
                'phones' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'pattern' => '(?:7|8)\d{10}',
                    ],
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'emails' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'pattern' => '^.+@.+\..+$',
                    ],
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'message' => [
                    'type' => 'string',
                    'pattern' => '^.{0,2000}$'
                ],
                'i_agree' => [
                    'enum' => [ true ],
                ],
                'grecaptchaToken' => [
                    'type' => 'string',
                ],
            ],
            'additionalProperties' => true,
            'required' => [ 
                'name',
                'phones',
                'emails',
                'message',
                'i_agree',
            ],
        ],
    ]);
});

$di->addShared('grecaptcha', function($di) {

    return new Websm\Framework\GreCAPTCHA('6LeNrpMUAAAAABVhoiyLCcR7VE3MmjR9-SVofafu', '6LeNrpMUAAAAAAr_hDelmaCrSPwzV_dlHoh3NLBi');
});


$di->addShared('auth-sms-api', function ($di) {

    return new API\Auth\V1\SMS\Controller([
        /* 'verifyRecaptcha' => false, */
        'validationSchema' => [
            'type' => 'object',
            'properties' => [
                'phone' => [
                    'type' => 'string',
                    'pattern' => '(?:7|8)\d{10}',
                ],
                'i_agree' => [
                    'enum' => [ true ],
                ],
                'grecaptchaToken' => [
                    'type' => 'string',
                ],
            ],
            'required' => [ 
                'phone',
                'i_agree',
                'grecaptchaToken',
            ],
        ],
    ]);
});



$di->addShared('orders-api', function ($di) {

    return new API\Orders\V1\Controller([
        'validationScheme' => [
            'type' => 'object',
            'properties' => [
                'ФИО' => [
                    'type' => 'string',
                    'pattern' => '^.{1,150}$'
                ],
                'Телефоны' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'pattern' => '(?:7|8)\d{10}',
                    ],
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'Электронные почты' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'pattern' => '^.+@.+\..+$',
                    ],
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'Способ получения' => [
                    'type' => 'string',
                    'enum' => [
                        'Самовывоз',
                        'Доставка',
                    ],
                ],
                'Способ оплаты' => [
                    'type' => 'string',
                    'enum' => [
                        'При получении',
                        'Онлайн',
                    ],
                ],
                'Согласие на обработку персональных данных' => [
                    'enum' => [ true ],
                ],
            ],
            'additionalProperties' => true,
            'required' => [ 
                'ФИО',
                'Телефоны',
                'Электронные почты',
                'Способ получения',
                'Способ оплаты',
            ],
        ],
    ]);
});

$di->addShared('dbf', function($di){
    return new Core\Misc\Dbf\Controller();
});

$di->addShared('voice', function() {

    $credentials = [
        "type" => "service_account",
        "project_id" => "text-to-speech-242213",
        "private_key_id" => "aef493b068a86edc53782a765f31c690985031f3",
        "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCXzRKAY65NQdTz\nHRBOFgSm2yZmcQ02o9wjVZghXfMDO/J7fP9QpPrUbVZ6uFycR6hg0Zo/dvmQl/Le\nyoOD8gTCQan7vjjTUTzhy48bV/4mQ2a9MeyVPl57ESZQ+gxL0cs5Bu+4bi9wP2pB\n5oBhuFg+Xv2ysuxm+x/qvMI2U7jzMWpJPNyJYYgsRVQz3tvjAvoVx8Mo7kkofaGN\nwJwVJKUm4CjWJxvnICnu3VHgxdjqFQ1zUjoFef+sxfvb+RCdYkGbT5k7Lq6WjAhT\nhy4fDPj+AbITj6ZUztgftRsV8w60rjQDNsndLQzIuzNwGbrYqlTAL/xJVw4g8Z93\nWF+LGO5fAgMBAAECggEAQXzd+3eFbZz18n/aVeWX0if/E/i3m5frU28Mt3Bhc0a8\nYFvXhYH3GAenZD/+7AjZ8hLauYBpP+DdjRHO1PBm7ysG2y0ANOH6Iur/bOt0ICu5\nues8xlHmzq7iWdLV1SvHwW1lo6odWw5wl8eKIR43K4ktZ5da9z5lUY5nNqq9wmQn\ne9D1hua1cfenC3/QUA25K1xiRYb5NnUQpBTbwPm1D01tbQ1J28AYghesg8C1vPmA\niPTGErWy9s6uGdTN0NsOzzsITTLxz10VZN79LQkvtzh5KH64DKiZM+uM2FCeTG5X\nrkBzZehl3Whu13nPioYOCqSKlDief5qkpMZb0MfbaQKBgQDVHGgMlkq+mFbbQP0w\nKtEz31Na2Ntsqstlf7hzaJxL1STwWXxL361bum9WKbkMn5bz9LjZGogV/arIwvDh\nn8stuN9EU8njnZDurpgWluvkT6u29q6Q7rgnZySk3hUec31ZGsTdi1s6jt0ASWyc\njcUZywVwkAC7lT3LBbMGOhYmgwKBgQC2WfI8j3o9XuXje8dnuInGDtqjD0bkKATy\nfn6Fk01pkCYvT3OGtG1vDG8VGab74nsB4pYE4fcDdTJH0a7KepSlIFiqz8SirUts\n7b0CuV+vtbINrxyaesEd5XWJHckbf7T12VmPeIL8LXqGWGg4z3VD7voFM8obF+3r\n+RWOkK0x9QKBgHFNH/iJMwXwAkWhYLrqGL2GX0+uecJlb6vv4oXhOS+MC46JVO8V\n5ZoeHbFmj06pzly3YEt0sqNWcSU5l02tYh1V68Xs8ipEjFiJlKx14sZIoVeuARNn\nB5tTWAUbWYkOB7eG5uGOhJs2d0L+xnLZYNAcwzCcLveMGgVFa2/FFB+3AoGAEJ6Q\nAk79UByXDS2Z1e0uuVtfN99Lkb3H/aXjuB7dvlJB+KvgFpXZJ3bVpPGqk2hn15sk\nGxvRHIKQ65TDvnZ/l7EkA6VeAYmTx0C/qkDk5KnFqZOdIsMvWJ8yICYTzyrLfmCp\n1fag0YT/lsD19r+Jq4BbTyASOnfq2kfgXBb5ggUCgYEAqqg9PEz/SVXPoChLJ8FJ\n5RIaWiaxTh6EU9nhmAiE+x9AVxrM3AetQk+sHdCLeMh6Q28WcTkxXtJIjEODK6Jj\nXfkF4e5MP8zNO+RZHtDvnlWD0UTn0GUZ13xt2TTZjbIyOlP8N1k0B8D4H2TkuoXu\n/1ck+Z9WNWaD3VSz9+p/QvI=\n-----END PRIVATE KEY-----\n",
        "client_email" => "text-to-speech-acount@text-to-speech-242213.iam.gserviceaccount.com",
        "client_id" => "104006629674958168848",
        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
        "token_uri" => "https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/text-to-speech-acount%40text-to-speech-242213.iam.gserviceaccount.com"
    ];

    return new \Websm\VoiceClients\GoogleTextToSpeech\Client([
        'credentials' => $credentials,
    ]);
});
