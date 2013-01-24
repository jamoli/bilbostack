<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

// Controladores relacionados con la parte de administración del sitio web
$backend = $app['controllers_factory'];

// Protección extra que asegura que al backend sólo acceden los administradores
$backend->before(function () use($app) {
    if (!$app['security']->isGranted('ROLE_ADMIN')) {
        return new RedirectResponse($app['url_generator']->generate('portada'));
    }
});

// -- PORTADA -----------------------------------------------------------------
$backend->get('/', function () use ($app) {
    $usuarios = array();

    return $app['twig']->render('backend/portada.twig', array(
        'usuarios' => $usuarios
    ));
});
 
return $backend;
