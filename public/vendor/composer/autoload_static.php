<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0536a51aa5b9e31d604bb995f7bad2ea
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Websm\\VoiceClients\\' => 19,
            'Websm\\Framework\\' => 16,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Cache\\' => 10,
        ),
        'J' => 
        array (
            'JsonSchema\\' => 11,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
            'Grpc\\Gcp\\' => 9,
            'Grpc\\' => 5,
            'Google\\Protobuf\\' => 16,
            'Google\\Cloud\\TextToSpeech\\' => 26,
            'Google\\Auth\\' => 12,
            'Google\\ApiCore\\' => 15,
            'Google\\' => 7,
            'GPBMetadata\\Google\\Protobuf\\' => 28,
            'GPBMetadata\\Google\\Cloud\\Texttospeech\\' => 38,
            'GPBMetadata\\Google\\' => 19,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
            'Faker\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Websm\\VoiceClients\\' => 
        array (
            0 => __DIR__ . '/..' . '/websm/voice-clients/src',
        ),
        'Websm\\Framework\\' => 
        array (
            0 => __DIR__ . '/..' . '/websm/framework/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'JsonSchema\\' => 
        array (
            0 => __DIR__ . '/..' . '/justinrainbow/json-schema/src/JsonSchema',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Grpc\\Gcp\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/grpc-gcp/src',
        ),
        'Grpc\\' => 
        array (
            0 => __DIR__ . '/..' . '/grpc/grpc/src/lib',
        ),
        'Google\\Protobuf\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/protobuf/src/Google/Protobuf',
        ),
        'Google\\Cloud\\TextToSpeech\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/cloud-text-to-speech/src',
        ),
        'Google\\Auth\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/auth/src',
        ),
        'Google\\ApiCore\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/gax/src',
        ),
        'Google\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/common-protos/src',
        ),
        'GPBMetadata\\Google\\Protobuf\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/protobuf/src/GPBMetadata/Google/Protobuf',
        ),
        'GPBMetadata\\Google\\Cloud\\Texttospeech\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/cloud-text-to-speech/metadata',
        ),
        'GPBMetadata\\Google\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/common-protos/metadata',
            1 => __DIR__ . '/..' . '/google/gax/metadata',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'Faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/fzaninotto/faker/src/Faker',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/..' . '/google/grpc-gcp/src/generated',
    );

    public static $prefixesPsr0 = array (
        'R' => 
        array (
            'Rs\\Json' => 
            array (
                0 => __DIR__ . '/..' . '/php-jsonpatch/php-jsonpatch/src',
                1 => __DIR__ . '/..' . '/php-jsonpointer/php-jsonpointer/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0536a51aa5b9e31d604bb995f7bad2ea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0536a51aa5b9e31d604bb995f7bad2ea::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit0536a51aa5b9e31d604bb995f7bad2ea::$fallbackDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit0536a51aa5b9e31d604bb995f7bad2ea::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
