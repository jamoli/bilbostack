<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Schema\Table;

$console = new Application('Herramientas de gestión de Bilbostack', '1.0');

$console
    ->register('crear_base_datos')
    ->setDefinition(array())
    ->setDescription("Crea las tablas adecuadas en la base de datos para que
        los usuarios se puedan registrar. Si ya existía la tabla, NO se borra.")
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $schema = $app['db']->getSchemaManager();

        if (!$schema->tablesExist('registro')) {
            $registro = new Table('registro');
            
            $registro->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $registro->setPrimaryKey(array('id'));
            $registro->addColumn('nombre', 'string', array('length' => 255));
            $registro->addColumn('email', 'string', array('length' => 255));
            $registro->addColumn('track', 'string', array('length' => 10));
            $registro->addColumn('comentarios', 'text');
 
            $schema->createTable($registro);
        }
        else {
            $output->writeln("La tabla 'registro' ya existía en la base de datos y por tanto,\nno se ha creado de nuevo ni se han borrado los datos existentes.");
        }
    })
;

return $console;
