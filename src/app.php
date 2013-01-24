<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider()); // requerido por los formularios
$app->register(new SecurityServiceProvider());
$app->register(new DoctrineServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    // descomenta esta línea para activar la cache de Twig
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));

// descomenta el siguiente código para añadir variables globales,
// activar filtros, functiones, etc. en Twig
// $app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

//     return $twig;
// }));

// activada la cache HTTP
$app->register(new HttpCacheServiceProvider(), array(
   'http_cache.cache_dir' => __DIR__.'/../cache/http',
   'http_cache.esi'       => null,
));

// configuración de la seguridad
$app['security.encoder.digest'] = $app->share(function ($app) {
    // algoritmo SHA-1, con 1 iteración y sin codificar en base64
    return new MessageDigestPasswordEncoder('sha1', false, 1);
});

$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/admin',
        'http'    => true,
        'users'   => array(
            // la contraseña sin codificar es "1234"
            'admin' => array('ROLE_ADMIN', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
        ),
    ),
);

// configuración de la base de datos
$app['db.options'] = array(
    'driver'   => 'pdo_sqlite',
    'path'     => __DIR__.'/../config/datos.sqlite',
);

return $app;
