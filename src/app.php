<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());

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
   'http_cache.esi'       => null
));

return $app;
