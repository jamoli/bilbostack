<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// -- BACKEND -----------------------------------------------------------------
$app->mount('/admin', include 'backend.php');

// -- PORTADA -----------------------------------------------------------------
$app->get('/', function () use ($app) {
    // la portada se cachea de forma pública durante 10 minutos
    return new Response(
        $app['twig']->render('portada.twig', array('ponentes' => $app['ponentes'])),
        200, array('Cache-Control' => 'public, max-age=600, s-maxage=600')
    );
})
->bind('portada');

// -- AGENDA ------------------------------------------------------------------
$app->get('/agenda', function () use ($app) {
    // insertar la información de cada ponente dentro de su ponencia
    // (las ponencias sólo guardan el slug del ponente, pero necesitamos
    // todos sus datos)
    $ponencias = array();
    foreach ($app['ponencias'] as $slug => $ponencia) {
        $slugPonente  = $ponencia['ponente'];
        $ponencia['ponente'] = $app['ponentes'][$slugPonente];
        $ponencias[$slug] = $ponencia;
    }

    return $app['twig']->render('agenda.twig', array(
        'ponencias' => $ponencias
    ));
})
->bind('agenda');

// -- LOCALIZACION / MAPAS ----------------------------------------------------
$app->get('/localizacion-mapas', function () use ($app) {
    return $app['twig']->render('localizacion.twig', array());
})
->bind('localizacion');

$app->get('/donde-comer', function () use ($app) {
    return $app['twig']->render('comer.twig', array());
})
->bind('comer');

// -- PONENTE -----------------------------------------------------------------
$app->get('/speakers/{slug}', function ($slug) use ($app) {
    if (!array_key_exists($slug, $app['ponentes'])) {
        $app->abort(404, "No existe el ponente $slug.");
    }

    // reemplazar en el objeto 'ponente' el 'slug' de su ponencia por
    // el objeto completo de esa ponencia
    $ponente = $app['ponentes'][$slug];
    $ponente['ponencia'] = $app['ponencias'][$ponente['ponencia']];

    return $app['twig']->render('ponente.twig', array(
        'ponente' => $ponente
    ));
})
->bind('ponente');

// -- PONENCIA ----------------------------------------------------------------
$app->get('/schedule/{slug}', function ($slug) use ($app) {
    if (!array_key_exists($slug, $app['ponencias'])) {
        $app->abort(404, "No existe la ponencia $slug.");
    }

    // reemplazar en el objeto 'ponencia' el 'slug' de su ponente por
    // el objeto completo de esa ponente
    $ponencia = $app['ponencias'][$slug];
    $ponencia['ponente'] = $app['ponentes'][$ponencia['ponente']];

    return $app['twig']->render('ponencia.twig', array(
        'ponencia' => $ponencia
    ));
})
->bind('ponencia');

// -- REGISTRO ----------------------------------------------------------------
$app->match('/registro', function (Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('nombre')
        ->add('email')
        ->add('track', 'choice', array(
            'choices'  => array(1 => 'Track 1 (programación)', 2 => 'Track 2 (diseño)'),
            'expanded' => true,
            'label'    => '¿Qué track te interesa más?'
        ))
        ->add('comentarios', 'textarea', array(
            'required' => false,
            'label'    => 'Comentarios (opcional)'
        ))
        ->getForm();
 
    if ('POST' == $request->getMethod()) {
        $form->bind($request);
 
        if ($form->isValid()) {
            $datos = $form->getData();
 
            // guardar los datos en una base de datos
            $app['db']->insert('registro', $datos);
 
            return $app['twig']->render('registro_completado.twig');
        }
    }

    return $app['twig']->render('registro.twig', array('form' => $form->createView()));
})
->bind('registro');

// -- PÁGINAS DE ERROR --------------------------------------------------------
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $paginaError = 404 == $code ? '404.twig' : '500.twig';

    return $app['twig']->render($paginaError, array(
        'mensaje' => $e->getMessage()
    ));
});
