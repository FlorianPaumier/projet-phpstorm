<?php

use App\Blog\BlogModule;
use Framework\Renderer\RendererInterface;
use GuzzleHttp\Psr7\ServerRequest;

require '../vendor/autoload.php';

$modules = [
    BlogModule::class
];
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__). '/config/config.php');

foreach ($modules as $module) {
    if ($module::DEFENITIONS) {
        $builder->addDefinitions($module::DEFENITIONS);
    }
}

$builder->addDefinitions(dirname(__DIR__). '/config.php');

$container = $builder->build();


    $app = new \Framework\App($container, $modules);

    $response = $app->run(ServerRequest::fromGlobals());

    \Http\Response\send($response);
